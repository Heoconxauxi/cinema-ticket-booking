<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChuDe;
use App\Models\Phim;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ChuDeController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        $search = $request->input('searchString', '');

        $query = ChuDe::with('phim'); // Eager load 'phim'

        if ($search) {
            $query->where(function ($q) use ($search) {
                // Tìm theo Tên Chủ Đề
                $q->where('TenChuDe', 'like', '%' . $search . '%')
                  // Hoặc tìm theo Tên Phim liên kết
                  ->orWhereHas('phim', function ($phimQuery) use ($search) {
                      $phimQuery->where('TenPhim', 'like', '%' . $search . '%');
                  });
            });
        }

        $list_chude = $query->orderBy('NgayTao', 'desc')->paginate($per_page);

        return view('admin.chude.index', compact('list_chude', 'per_page', 'search'));
    }

    public function create()
    {
        // Lấy danh sách phim để chọn (ưu tiên phim đang ON)
        $phims = Phim::where('TrangThai', 1)->orderBy('TenPhim')->get();
        return view('admin.chude.create', compact('phims'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'TenChuDe' => 'required|string|max:255|unique:chude,TenChuDe',
            'MaPhim' => 'nullable|exists:phim,MaPhim',
            'MoTa' => 'required|string|max:255',
            'TuKhoa' => 'required|string|max:255',
            'TrangThai' => 'nullable'
        ]);

        $data['NguoiTao'] = Auth::id() ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;
        $data['TenRutGon'] = Str::slug($data['TenChuDe']);

        ChuDe::create($data);

        return redirect()->route('admin.chude.index')->with('success', 'Thêm chủ đề thành công!');
    }

    public function edit(ChuDe $chude)
    {
        $phims = Phim::where('TrangThai', 1)->orderBy('TenPhim')->get();
        return view('admin.chude.edit', compact('chude', 'phims'));
    }

    public function update(Request $request, ChuDe $chude)
    {
        $data = $request->validate([
            'TenChuDe' => [
                'required', 'string', 'max:255',
                Rule::unique('chude')->ignore($chude->Id, 'Id')
            ],
            'MaPhim' => 'nullable|exists:phim,MaPhim',
            'MoTa' => 'required|string|max:255',
            'TuKhoa' => 'required|string|max:255',
            'TrangThai' => 'nullable'
        ]);

        $data['NguoiCapNhat'] = Auth::id() ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;
        $data['TenRutGon'] = Str::slug($data['TenChuDe']);

        $chude->update($data);

        return redirect()->route('admin.chude.index')->with('success', 'Cập nhật chủ đề thành công!');
    }

    public function destroy(ChuDe $chude)
    {
        // CẢNH BÁO: Schema của bạn có ON DELETE CASCADE
        // Lệnh này sẽ XÓA VĨNH VIỄN tất cả BÀI VIẾT thuộc chủ đề này.
        $chude->delete();

        return redirect()->route('admin.chude.index')->with('success', 'Xóa chủ đề thành công! (Tất cả bài viết liên quan cũng đã bị xóa)');
    }
}