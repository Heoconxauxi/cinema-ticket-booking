<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Phim;
use App\Models\TheLoai;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule; // Thêm Rule để validate unique khi update
use Illuminate\Support\Facades\Auth;

class PhimController extends Controller
{
    /**
     * Hiển thị danh sách phim (trang index).
     */
    public function index(Request $request)
    {
        // Lấy tham số tìm kiếm và phân trang từ request
        $search = $request->input('searchString', '');
        $per_page = $request->input('per_page', 5); // Lấy per_page, mặc định 5

        // Query Phim, eager load theLoais
        $query = Phim::with('theLoais');

        // Áp dụng bộ lọc tìm kiếm nếu có
        if ($search) {
            $query->where('TenPhim', 'like', '%' . $search . '%');
        }

        // Lấy danh sách, sắp xếp và phân trang
        $list_phim = $query->orderBy('NgayTao', 'desc')->paginate($per_page);

        // Truyền dữ liệu ra view
        return view('admin.phim.index', compact('list_phim', 'search', 'per_page'));
    }

    /**
     * Hiển thị form tạo phim mới.
     */
    public function create()
    {
        $the_loais = TheLoai::where('TrangThai', 1)->orderBy('TenTheLoai')->get();
        // Định nghĩa sẵn các quốc gia
        $defined_nations = ['Âu Mỹ', 'Hàn Quốc', 'Trung Quốc', 'Anh', 'Việt Nam', 'Nhật Bản', 'Thái Lan'];
        return view('admin.phim.create', compact('the_loais', 'defined_nations'));
    }

    /**
     * Lưu phim mới vào database.
     */
    public function store(Request $request)
    {
        // === SỬA VALIDATION ===
        $data = $request->validate([
            'TenPhim' => 'required|string|max:255|unique:phim,TenPhim',
            'ThoiLuong' => 'required|integer|min:1',
            'PhanLoai' => 'required|string',
            'DaoDien' => 'required|string|max:255',
            'DienVien' => 'required|string|max:255',
            'NamPhatHanh' => 'required|integer',
            'MoTa' => 'required|string',
            // Đổi validation cho ảnh: url và nullable (hoặc required nếu bạn muốn bắt buộc nhập URL)
            'Anh' => 'required|url|max:1000', // Giới hạn độ dài URL nếu cần
            'Banner' => 'nullable|url|max:1000',
            'the_loai' => 'nullable|array',
            'the_loai.*' => 'exists:theloai,MaTheLoai',
            'quoc_gia' => 'nullable|array',
            'other_nation' => 'nullable|string|max:255',
            'TrangThai' => 'required|integer|in:0,1,2',
        ]);

        // 1. Xử lý Quốc Gia (giữ nguyên)
        $nations = $request->input('quoc_gia', []);
        if ($request->filled('other_nation')) {
            $otherNationsArray = array_map('trim', explode(',', $request->input('other_nation')));
            $nations = array_merge($nations, $otherNationsArray);
        }
        $data['QuocGia'] = implode(', ', array_unique($nations));

        // === BỎ HOÀN TOÀN CODE XỬ LÝ FILE ===
        /*
        // 2. Xử lý file 'Anh' (Poster) - BỎ
        if ($request->hasFile('Anh')) { ... }

        // 3. Xử lý file 'Banner' - BỎ
        if ($request->hasFile('Banner')) { ... }
        */
        // === GÁN TRỰC TIẾP URL ===
        // Dữ liệu 'Anh' và 'Banner' đã có sẵn trong $data từ validate, không cần làm gì thêm.

        // 4. Các trường khác (giữ nguyên)
        $data['TenRutGon'] = Str::slug($data['TenPhim']);
        $data['NguoiTao'] = Auth::Id() ?? 0;

        // 5. Tạo Phim (giữ nguyên)
        $phim = Phim::create($data);

        // 6. Đồng bộ thể loại (giữ nguyên)
        if (!empty($data['the_loai'])) {
            $phim->theLoais()->sync($data['the_loai']);
        }

        return redirect()->route('admin.phim.index')->with('success', 'Thêm phim mới thành công!');
    }

    /**
     * Hiển thị chi tiết một phim.
     */
    public function show(Phim $phim)
    {
        $phim->load(['theLoais', 'nguoiTao', 'nguoiCapNhat']); // Eager load các quan hệ cần thiết
         // Giả sử bạn đã định nghĩa quan hệ BelongsTo 'nguoiTao' và 'nguoiCapNhat' trỏ đến model User/TaiKhoan
        return view('admin.phim.show', compact('phim'));
    }

    /**
     * Hiển thị form chỉnh sửa phim.
     */
    public function edit(Phim $phim)
    {
        $the_loais = TheLoai::where('TrangThai', 1)->orderBy('TenTheLoai')->get();
        $phim_theloais_ids = $phim->theLoais->pluck('MaTheLoai')->toArray();

        // Xử lý Quốc gia để pre-fill
        $defined_nations = ['Âu Mỹ', 'Hàn Quốc', 'Trung Quốc', 'Anh', 'Việt Nam', 'Nhật Bản', 'Thái Lan'];
        $selected_nations = array_map('trim', explode(',', $phim->QuocGia));
        $other_nation = implode(', ', array_diff($selected_nations, $defined_nations));
        // Lọc ra các quốc gia đã chọn nằm trong defined_nations
        $phim_nations = array_intersect($selected_nations, $defined_nations);

        return view('admin.phim.edit', compact(
            'phim',
            'the_loais',
            'phim_theloais_ids',
            'defined_nations',
            'phim_nations', // Các checkbox được check
            'other_nation' // Phần nhập khác
        ));
    }

    /**
     * Cập nhật thông tin phim.
     */
    public function update(Request $request, Phim $phim)
    {
        // === SỬA VALIDATION ===
         $data = $request->validate([
            'TenPhim' => ['required', 'string', 'max:255', Rule::unique('phim')->ignore($phim->MaPhim, 'MaPhim')],
            'ThoiLuong' => 'required|integer|min:1',
            'PhanLoai' => 'required|string',
            'DaoDien' => 'required|string|max:255',
            'DienVien' => 'required|string|max:255',
            'NamPhatHanh' => 'required|integer',
            'MoTa' => 'required|string',
            // Đổi validation ảnh: url và nullable (ảnh không bắt buộc khi update)
            'Anh' => 'nullable|url|max:1000',
            'Banner' => 'nullable|url|max:1000',
            'the_loai' => 'nullable|array',
            'the_loai.*' => 'exists:theloai,MaTheLoai',
            'quoc_gia' => 'nullable|array',
            'other_nation' => 'nullable|string|max:255',
            'TrangThai' => 'required|integer|in:0,1,2',
        ]);

        // 1. Xử lý Quốc Gia (giữ nguyên)
        $nations = $request->input('quoc_gia', []);
        if ($request->filled('other_nation')) {
            $otherNationsArray = array_map('trim', explode(',', $request->input('other_nation')));
            $nations = array_merge($nations, $otherNationsArray);
        }
        $data['QuocGia'] = implode(', ', array_unique($nations));

        // === BỎ HOÀN TOÀN CODE XỬ LÝ FILE ===
        /*
        // 2. Xử lý ảnh MỚI (nếu có) - BỎ
        if ($request->hasFile('Anh')) { ... }

        // 3. Xử lý banner MỚI - BỎ
        if ($request->hasFile('Banner')) { ... }
        */
        // === GÁN TRỰC TIẾP URL ===
        // Nếu người dùng không nhập URL mới, $data['Anh'] và $data['Banner'] sẽ là null.
        // Chúng ta cần xử lý để không ghi đè URL cũ bằng null nếu không muốn.
        // Cách đơn giản: Nếu input rỗng thì giữ nguyên giá trị cũ.
        if (empty($data['Anh'])) {
            unset($data['Anh']); // Không cập nhật 'Anh' nếu input rỗng
        }
         if (empty($data['Banner'])) {
            unset($data['Banner']); // Không cập nhật 'Banner' nếu input rỗng
        }


        // 4. Các trường khác (giữ nguyên)
        $data['TenRutGon'] = Str::slug($data['TenPhim']);
        $data['NguoiCapNhat'] = Auth::Id() ?? 0;

        // 5. Cập nhật Phim (giữ nguyên)
        $phim->update($data);

        // 6. Đồng bộ lại thể loại (giữ nguyên)
        $phim->theLoais()->sync($request->input('the_loai', []));

        return redirect()->route('admin.phim.index')->with('success', 'Cập nhật phim thành công!');
    }

    /**
     * Xóa phim khỏi database.
     */
    public function destroy(Phim $phim)
    {
        $phim->delete();

        return redirect()->route('admin.phim.index')->with('success', 'Xóa phim thành công!');
    }
}