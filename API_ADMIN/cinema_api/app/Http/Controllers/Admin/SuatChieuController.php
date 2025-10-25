<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SuatChieu;
use App\Models\Phim;
use App\Models\Phong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuatChieuController extends Controller
{
    /**
     * Hiển thị danh sách suất chiếu.
     */
    public function index(Request $request)
    {
        $search = $request->input('searchString', '');
        
        // Lấy 'per_page' từ request, mặc định là 10
        $per_page = $request->input('per_page', 5);
        
        $query = SuatChieu::with(['phim', 'phong']);

        if ($search) {
            $query->whereHas('phim', function ($q) use ($search) {
                $q->where('TenPhim', 'like', '%' . $search . '%');
            });
        }

        // Thay paginate(10) bằng paginate($per_page)
        $list_suatchieu = $query->orderBy('GioChieu', 'desc')->paginate($per_page);

        // Truyền $per_page ra view
        return view('admin.suatchieu.index', compact('list_suatchieu', 'search', 'per_page'));
    }

    /**
     * Hiển thị form tạo suất chiếu mới.
     */
    public function create()
    {
        // Lấy danh sách phim (đang ON) và phòng (đang ON) để cho vào <select>
        $phims = Phim::where('TrangThai', 1)->orderBy('TenPhim')->get();
        $phongs = Phong::where('TrangThai', 1)->orderBy('TenPhong')->get();

        return view('admin.suatchieu.create', compact('phims', 'phongs'));
    }

    /**
     * Lưu suất chiếu mới.
     */
    public function store(Request $request)
    {
        // 1. Validate
        $data = $request->validate([
            'MaPhim' => 'required|exists:phim,MaPhim',
            'MaPhong' => 'required|exists:phong,MaPhong',
            'GioChieu' => 'required|date',
            'TrangThai' => 'nullable',
        ]);

        // 2. Thêm dữ liệu
        $data['NguoiTao'] = Auth::id() ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;

        // 3. Tạo
        SuatChieu::create($data);

        return redirect()->route('admin.suatchieu.index')->with('success', 'Thêm suất chiếu thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa suất chiếu.
     */
    public function edit(SuatChieu $suatchieu)
    {
        // Lấy danh sách phim và phòng
        $phims = Phim::where('TrangThai', 1)->orderBy('TenPhim')->get();
        $phongs = Phong::where('TrangThai', 1)->orderBy('TenPhong')->get();

        return view('admin.suatchieu.edit', compact('suatchieu', 'phims', 'phongs'));
    }

    /**
     * Cập nhật suất chiếu.
     */
    public function update(Request $request, SuatChieu $suatchieu)
    {
        // 1. Validate
        $data = $request->validate([
            'MaPhim' => 'required|exists:phim,MaPhim',
            'MaPhong' => 'required|exists:phong,MaPhong',
            'GioChieu' => 'required|date',
            'TrangThai' => 'nullable',
        ]);

        // 2. Thêm dữ liệu
        $data['NguoiCapNhat'] = Auth::id() ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;

        // 3. Cập nhật
        $suatchieu->update($data);

        return redirect()->route('admin.suatchieu.index')->with('success', 'Cập nhật suất chiếu thành công!');
    }

    /**
     * Xóa suất chiếu.
     */
    public function destroy(SuatChieu $suatchieu)
    {
        $suatchieu->delete();

        return redirect()->route('admin.suatchieu.index')->with('success', 'Xóa suất chiếu thành công!');
    }
}