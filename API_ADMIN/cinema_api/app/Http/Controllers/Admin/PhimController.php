<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Phim;
use App\Models\TheLoai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhimController extends Controller
{
    /**
     * Danh sách quốc gia có sẵn, dùng chung cho create và edit
     */
    private $defined_nations = [
        'Việt Nam', 'Mỹ', 'Hàn Quốc', 'Nhật Bản', 
        'Trung Quốc', 'Anh', 'Pháp', 'Thái Lan', 'Âu Mỹ'
    ];

    /**
     * Hiển thị danh sách phim (trang index).
     */
    public function index(Request $request)
    {
        // 1. Lấy giá trị per_page từ request, mặc định là 10 (bạn có thể đổi thành 5)
        $perPage = $request->input('per_page', 5);

        // 2. Validate giá trị để đảm bảo nó là 5, 10, hoặc 20
        if (!in_array($perPage, [5, 10, 20])) {
            $perPage = 5; // Nếu giá trị không hợp lệ, quay về mặc định
        }

        $search = $request->input('search', '');
        $query = Phim::with('theLoais');

        if ($search) {
            $query->where('TenPhim', 'like', '%' . $search . '%');
        }

        // 3. Sử dụng biến $perPage thay vì số 5 cứng
        $list_phim = $query->orderBy('NgayTao', 'desc')->paginate($perPage);
        
        // 4. Trả về view
        return view('admin.phim.index', compact('list_phim', 'search'));
    }

    /**
     * Hiển thị form tạo phim mới.
     */
    public function create()
    {
        $the_loais = TheLoai::where('TrangThai', 1)->get();
        
        // **SỬA: Truyền danh sách quốc gia sang view**
        $defined_nations = $this->defined_nations;
        
        return view('admin.phim.create', compact('the_loais', 'defined_nations'));
    }

    /**
     * Lưu phim mới vào database.
     */
    public function store(Request $request)
    {
        // **SỬA: Thay đổi validation cho QuocGia**
        $request->validate([
            'TenPhim' => 'required|string|max:255|unique:phim',
            'ThoiLuong' => 'required|integer|min:1',
            // 'QuocGia' không cần validate trực tiếp
            'quoc_gia' => 'nullable|array', // Đây là mảng checkbox
            'other_nation' => 'nullable|string', // Đây là ô "Khác"
            'DaoDien' => 'required|string|max:255',
            'DienVien' => 'required|string|max:255',
            'NamPhatHanh' => 'required|integer',
            'PhanLoai' => 'required|string|max:50',
            'MoTa' => 'required|string',
            'Anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'the_loais' => 'nullable|array',
            'the_loais.*' => 'exists:theloai,MaTheLoai',
        ]);

        // Lấy tất cả dữ liệu, ngoại trừ 2 trường quốc gia ảo
        $data = $request->except(['quoc_gia', 'other_nation']);

        // **SỬA: Gộp 2 trường quốc gia thành 1 chuỗi JSON**
        $quocGiaData = [
            'selected' => $request->input('quoc_gia', []),
            'other' => $request->input('other_nation', '')
        ];
        
        // Lưu chuỗi JSON vào cột 'QuocGia'
        $data['QuocGia'] = json_encode($quocGiaData);
        // **KẾT THÚC SỬA**


        if ($request->hasFile('Anh')) {
            $path = $request->file('Anh')->store('uploads/phim', 'public');
            $data['Anh'] = $path;
        }

        if ($request->hasFile('Banner')) {
            $path_banner = $request->file('Banner')->store('uploads/banners', 'public');
            $data['Banner'] = $path_banner;
        }

        $data['TenRutGon'] = Str::slug($data['TenPhim']);
        $data['NguoiTao'] = auth()->id ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;

        $phim = Phim::create($data);

        if (!empty($request->input('the_loais'))) {
            $phim->theLoais()->sync($request->input('the_loais'));
        }

        return redirect()->route('phim.index')->with('success', 'Thêm phim mới thành công!');
    }

    /**
     * Hiển thị chi tiết một phim.
     */
    public function show(Phim $phim)
    {
        $phim->load('theLoais');
        return view('admin.phim.show', compact('phim'));
    }

    /**
     * Hiển thị form chỉnh sửa phim.
     */
    public function edit(Phim $phim)
    {
        $the_loais = TheLoai::where('TrangThai', 1)->get();
        $phim_theloais_ids = $phim->theLoais->pluck('MaTheLoai')->toArray();
        $defined_nations = $this->defined_nations;

        $phim_nations = []; // Mảng cho checkbox
        $other_nation = ''; // Chuỗi cho ô "Khác"

        if (!empty($phim->QuocGia)) {
            $quocGiaData = json_decode($phim->QuocGia, true);
            
            // Kiểm tra xem có phải là JSON hợp lệ VÀ có key 'selected' không
            if (json_last_error() === JSON_ERROR_NONE && is_array($quocGiaData) && isset($quocGiaData['selected'])) {
                // *** Trường hợp 1: Dữ liệu LÀ JSON (đã lưu theo logic mới) ***
                $phim_nations = $quocGiaData['selected'] ?? [];
                $other_nation = $quocGiaData['other'] ?? '';
            } else {
                // *** Trường hợp 2: Dữ liệu CŨ (là một chuỗi, không phải JSON) ***
                $old_data_string = $phim->QuocGia;
                
                // Tách chuỗi cũ (giả sử cách nhau bằng dấu phẩy)
                $old_nations = array_map('trim', explode(',', $old_data_string));
                
                foreach ($old_nations as $nation) {
                    if (in_array($nation, $defined_nations)) {
                        // Nếu quốc gia có trong danh sách, check nó
                        $phim_nations[] = $nation;
                    } else if (!empty($nation)) {
                        // Nếu không, thêm vào ô "Khác"
                        $other_nation = empty($other_nation) ? $nation : $other_nation . ', ' . $nation;
                    }
                }
            }
        }

        return view('admin.phim.edit', compact(
            'phim',
            'the_loais',
            'phim_theloais_ids',
            'defined_nations',   // <-- Truyền biến mới
            'phim_nations',      // <-- Truyền biến mới
            'other_nation'       // <-- Truyền biến mới
        ));
    }

    /**
     * Cập nhật phim.
     */
    public function update(Request $request, Phim $phim)
    {
        // **SỬA: Thay đổi validation cho QuocGia**
        $request->validate([
            'TenPhim' => 'required|string|max:255|unique:phim,TenPhim,' . $phim->MaPhim . ',MaPhim',
            'ThoiLuong' => 'required|integer|min:1',
            'quoc_gia' => 'nullable|array',
            'other_nation' => 'nullable|string',
            'DaoDien' => 'required|string|max:255',
            'DienVien' => 'required|string|max:255',
            'NamPhatHanh' => 'required|integer',
            'PhanLoai' => 'required|string|max:50',
            'MoTa' => 'required|string',
            'Anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'the_loais' => 'nullable|array',
            'the_loais.*' => 'exists:theloai,MaTheLoai',
        ]);

        $data = $request->except(['quoc_gia', 'other_nation']);

        // **SỬA: Gộp 2 trường quốc gia thành 1 chuỗi JSON**
        $quocGiaData = [
            'selected' => $request->input('quoc_gia', []),
            'other' => $request->input('other_nation', '')
        ];
        $data['QuocGia'] = json_encode($quocGiaData);
        // **KẾT THÚC SỬA**
        

        if ($request->hasFile('Anh')) {
            if ($phim->Anh) Storage::disk('public')->delete($phim->Anh);
            $path = $request->file('Anh')->store('uploads/phim', 'public');
            $data['Anh'] = $path;
        }

        if ($request->hasFile('Banner')) {
            if ($phim->Banner) Storage::disk('public')->delete($phim->Banner);
            $path_banner = $request->file('Banner')->store('uploads/banners', 'public');
            $data['Banner'] = $path_banner;
        }

        $data['TenRutGon'] = Str::slug($data['TenPhim']);
        $data['NguoiCapNhat'] = auth()->id ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;

        $phim->update($data);

        $phim->theLoais()->sync($request->input('the_loais', []));

        return redirect()->route('phim.index')->with('success', 'Cập nhật phim thành công!');
    }

    /**
     * Xóa phim.
     */
    public function destroy(Phim $phim)
    {
        if ($phim->Anh) {
            Storage::disk('public')->delete($phim->Anh);
        }
        if ($phim->Banner) {
            Storage::disk('public')->delete($phim->Banner);
        }
        
        $phim->delete();

        return redirect()->route('phim.index')->with('success', 'Xóa phim thành công!');
    }
}