<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use App\Models\ChuDe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BaiVietController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        $search = $request->input('searchString', '');

        $query = BaiViet::with('chuDe'); // Eager load 'chuDe'

        if ($search) {
            $query->where(function ($q) use ($search) {
                // Tìm theo Tên Bài Viết
                $q->where('TenBV', 'like', '%' . $search . '%')
                  // Hoặc tìm theo Tên Chủ Đề
                  ->orWhereHas('chuDe', function ($chuDeQuery) use ($search) {
                      $chuDeQuery->where('TenChuDe', 'like', '%' . $search . '%');
                  });
            });
        }

        $list_baiviet = $query->orderBy('NgayTao', 'desc')->paginate($per_page);

        return view('admin.baiviet.index', compact('list_baiviet', 'per_page', 'search'));
    }

    public function create()
    {
        $chudes = ChuDe::where('TrangThai', 1)->orderBy('TenChuDe')->get();
        return view('admin.baiviet.create', compact('chudes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'TenBV' => 'required|string|max:255|unique:baiviet,TenBV',
            'ChuDeBV' => 'required|exists:chude,Id',
            'KieuBV' => 'nullable|string|max:255',
            'MoTa' => 'required|string',
            'TuKhoa' => 'required|string|max:255',
            'ChiTiet' => 'required|string',
            'Anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'TrangThai' => 'nullable'
        ]);

        $data['NguoiTao'] = auth()->id ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;
        $data['LienKet'] = Str::slug($data['TenBV']);

        // Xử lý Upload Ảnh
        if ($request->hasFile('Anh')) {
            // Lưu file vào 'storage/app/public/uploads/baiviet'
            // Đường dẫn trong DB sẽ là 'uploads/baiviet/ten_file.jpg'
            $path = $request->file('Anh')->store('uploads/baiviet', 'public');
            $data['Anh'] = basename($path);
        }

        BaiViet::create($data);

        return redirect()->route('admin.baiviet.index')->with('success', 'Thêm bài viết thành công!');
    }


    public function show(BaiViet $baiviet)
    {
        // Tải thông tin chủ đề liên quan (nếu cần hiển thị tên chủ đề)
        $baiviet->load('chuDe'); 
        
        // Trả về view 'show.blade.php' và truyền biến $baiviet
        return view('admin.baiviet.show', compact('baiviet'));
    }
    
    public function edit(BaiViet $baiviet)
    {
        $chudes = ChuDe::where('TrangThai', 1)->orderBy('TenChuDe')->get();
        return view('admin.baiviet.edit', compact('baiviet', 'chudes'));
    }

    public function update(Request $request, BaiViet $baiviet)
    {
        $data = $request->validate([
            'TenBV' => [
                'required', 'string', 'max:255',
                Rule::unique('baiviet')->ignore($baiviet->Id, 'Id')
            ],
            'ChuDeBV' => 'required|exists:chude,Id',
            'KieuBV' => 'nullable|string|max:255',
            'MoTa' => 'required|string',
            'TuKhoa' => 'required|string|max:255',
            'ChiTiet' => 'required|string',
            'Anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'TrangThai' => 'nullable'
        ]);

        $data['NguoiCapNhat'] = auth()->id ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;
        $data['LienKet'] = Str::slug($data['TenBV']);

        // Xử lý Upload Ảnh (nếu có ảnh mới)
        if ($request->hasFile('Anh')) {
            // 1. Xóa ảnh cũ
            if ($baiviet->Anh) {
                Storage::disk('public')->delete('uploads/baiviet/' . $baiviet->Anh);
            }
            // 2. Lưu ảnh mới
            $path = $request->file('Anh')->store('uploads/baiviet', 'public');
            $data['Anh'] = basename($path);
        }

        $baiviet->update($data);

        return redirect()->route('admin.baiviet.index')->with('success', 'Cập nhật bài viết thành công!');
    }

    public function destroy(BaiViet $baiviet)
    {
        // 1. Xóa ảnh liên quan
        if ($baiviet->Anh) {
            Storage::disk('public')->delete('uploads/baiviet/' . $baiviet->Anh);
        }
        
        // 2. Xóa bài viết
        $baiviet->delete();

        return redirect()->route('admin.baiviet.index')->with('success', 'Xóa bài viết thành công!');
    }
}