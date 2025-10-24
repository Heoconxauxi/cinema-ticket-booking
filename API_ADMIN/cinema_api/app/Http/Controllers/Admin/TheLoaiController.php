<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TheLoai;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class TheLoaiController extends Controller
{
    /**
     * Hiển thị danh sách thể loại.
     */
    public function index(Request $request)
    {
        $search = $request->input('searchString', '');
        
        // Lấy 'per_page' từ request, mặc định là 10
        $per_page = $request->input('per_page', 5); 
        
        $query = TheLoai::query();

        if ($search) {
            $query->where('TenTheLoai', 'like', '%' . $search . '%');
        }

        // Thay paginate(10) bằng paginate($per_page)
        $list_theloai = $query->orderBy('NgayTao', 'desc')->paginate($per_page);

        // Truyền $per_page ra view
        return view('admin.theloai.index', compact('list_theloai', 'search', 'per_page'));
    }

    /**
     * Hiển thị form tạo thể loại mới.
     */
    public function create()
    {
        return view('admin.theloai.create');
    }

    /**
     * Lưu thể loại mới vào database.
     */
    public function store(Request $request)
    {
        // 1. Validate dữ liệu
        $data = $request->validate([
            'TenTheLoai' => 'required|string|max:255|unique:theloai,TenTheLoai',
            'TrangThai' => 'nullable' // Sẽ xử lý on/off
        ]);

        // 2. Thêm các trường NguoiTao và TrangThai
        $data['NguoiTao'] = Auth::id() ?? 0;
        // Nếu checkbox 'TrangThai' được check thì là 1, ngược lại là 0
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0; 

        // 3. Tạo mới
        TheLoai::create($data);

        return redirect()->route('admin.theloai.index')->with('success', 'Thêm thể loại thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa thể loại.
     * Laravel tự động tìm $theloai dựa trên MaTheLoai từ URL (Route Model Binding).
     */
    public function edit(TheLoai $theloai)
    {
        return view('admin.theloai.edit', compact('theloai'));
    }

    /**
     * Cập nhật thể loại trong database.
     */
    public function update(Request $request, TheLoai $theloai)
    {
        // 1. Validate dữ liệu
        $data = $request->validate([
            'TenTheLoai' => [
                'required',
                'string',
                'max:255',
                // Rule unique: Bỏ qua MaTheLoai hiện tại khi kiểm tra
                Rule::unique('theloai')->ignore($theloai->MaTheLoai, 'MaTheLoai')
            ],
            'TrangThai' => 'nullable'
        ]);

        // 2. Thêm các trường NguoiCapNhat và TrangThai
        $data['NguoiCapNhat'] = Auth::id() ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;

        // 3. Cập nhật
        $theloai->update($data);

        return redirect()->route('admin.theloai.index')->with('success', 'Cập nhật thể loại thành công!');
    }

    /**
     * Xóa thể loại khỏi database.
     */
    public function destroy(TheLoai $theloai)
    {
        // 1. (QUAN TRỌNG) Xóa các liên kết trong bảng pivot 'theloai_film'
        // Mặc dù file SQL của bạn có TRIGGER, đây là cách làm chuẩn của Laravel
        // để đảm bảo ứng dụng quản lý đúng các mối quan hệ.
        $theloai->phims()->detach();

        // 2. Xóa thể loại
        $theloai->delete();

        return redirect()->route('admin.theloai.index')->with('success', 'Xóa thể loại thành công!');
    }
}