<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Phong;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PhongController extends Controller
{
    /**
     * Hiển thị danh sách phòng
     */
    public function index(Request $request)
    {
        // Lấy 'per_page' từ request, mặc định là 10
        $per_page = $request->input('per_page', 10); 
        
        // --- LOGIC TÌM KIẾM (ĐỂ LẠI CHO BẠN PHÁT TRIỂN SAU) ---
        // $search = $request->input('searchString', ''); 
        // ------------------------------------------------------
        
        $query = Phong::query(); // Bắt đầu query

        // --- LOGIC TÌM KIẾM (ĐỂ LẠI CHO BẠN PHÁT TRIỂN SAU) ---
        // if ($search) {
        //     $query->where('TenPhong', 'like', '%' . $search . '%');
        // }
        // ------------------------------------------------------

        // Sắp xếp theo TenPhong (Rạp 1, Rạp 2...)
        $list_phong = $query->orderBy('TenPhong', 'asc')->paginate($per_page);

        // Không truyền 'search' ra view
        return view('admin.phong.index', compact('list_phong', 'per_page'));
    }

    /**
     * Hiển thị form tạo phòng mới.
     */
    public function create()
    {
        return view('admin.phong.create');
    }

    /**
     * Lưu phòng mới vào database.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'TenPhong' => 'required|string|max:255|unique:phong,TenPhong',
            'TrangThai' => 'nullable'
        ]);

        $data['NguoiTao'] = auth()->id ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0; 

        Phong::create($data);

        return redirect()->route('admin.phong.index')->with('success', 'Thêm phòng mới thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa phòng.
     */
    public function edit(Phong $phong)
    {
        return view('admin.phong.edit', compact('phong'));
    }

    /**
     * Cập nhật phòng.
     */
    public function update(Request $request, Phong $phong)
    {
        $data = $request->validate([
            'TenPhong' => [
                'required',
                'string',
                'max:255',
                Rule::unique('phong')->ignore($phong->MaPhong, 'MaPhong')
            ],
            'TrangThai' => 'nullable'
        ]);

        $data['NguoiCapNhat'] = auth()->id ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;

        $phong->update($data);

        return redirect()->route('admin.phong.index')->with('success', 'Cập nhật phòng thành công!');
    }

    /**
     * Xóa phòng.
     */
    public function destroy(Phong $phong)
    {
        // CẢNH BÁO: Dựa trên file project_film.sql của bạn,
        // Bảng 'ghe' và 'suatchieu' đều có "ON DELETE CASCADE".
        // Lệnh $phong->delete() sẽ XÓA VĨNH VIỄN tất cả Ghế 
        // và tất cả Suất Chiếu thuộc phòng này.
        
        // (Cách an toàn hơn là check trước, nhưng chúng ta tuân theo schema của bạn)
        // if ($phong->suatChieus()->count() > 0 || $phong->ghes()->count() > 0) {
        //     return back()->with('error', 'Không thể xóa phòng đã có ghế hoặc suất chiếu.');
        // }

        $phong->delete();

        return redirect()->route('admin.phong.index')->with('success', 'Xóa phòng thành công! (Tất cả ghế và suất chiếu liên quan cũng đã bị xóa)');
    }
}