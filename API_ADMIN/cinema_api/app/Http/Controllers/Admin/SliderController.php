<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\Phim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SliderController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 5); // Ít slider, 5 là đủ
        $search = $request->input('searchString', '');

        $query = Slider::with('phim'); // Eager load 'phim'

        if ($search) {
            $query->where(function ($q) use ($search) {
                // Tìm theo Tên Slider hoặc Vị Trí
                $q->where('TenSlider', 'like', '%' . $search . '%')
                  ->orWhere('ViTri', 'like', '%' . $search . '%')
                  // Hoặc tìm theo Tên Phim liên kết
                  ->orWhereHas('phim', function ($phimQuery) use ($search) {
                      $phimQuery->where('TenPhim', 'like', '%' . $search . '%');
                  });
            });
        }

        // Sắp xếp theo Vị trí -> Thứ tự -> Ngày tạo
        $list_slider = $query->orderBy('ViTri', 'asc')
                             ->orderBy('SapXep', 'asc')
                             ->orderBy('NgayTao', 'desc')
                             ->paginate($per_page);

        return view('admin.slider.index', compact('list_slider', 'per_page', 'search'));
    }

    public function create()
    {
        $phims = Phim::where('TrangThai', 1)->orderBy('TenPhim')->get();
        // Lấy các vị trí đã có để gợi ý (hoặc bạn có thể định nghĩa sẵn)
        $viTris = Slider::distinct()->pluck('ViTri')->toArray();
        return view('admin.slider.create', compact('phims', 'viTris'));
    }

    public function store(Request $request)
    {
        // === SỬA VALIDATION ===
        $data = $request->validate([
            'TenSlider' => 'required|string|max:255',
            'URL' => 'nullable|url|max:255',
            'MaPhim' => 'nullable|exists:phim,MaPhim',
            // Ảnh slider là bắt buộc và phải là URL
            'Anh' => 'required|url|max:1000', 
            'SapXep' => 'nullable|integer',
            'ViTri' => 'required|string|max:255',
            'MoTa' => 'required|string|max:255',
            'TuKhoa' => 'required|string|max:255',
            'TenChuDe' => 'required|string|max:255', 
            'TrangThai' => 'nullable'
        ]);

        $data['NguoiTao'] = Auth::Id() ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;
        $data['SapXep'] = $data['SapXep'] ?? 0;

        // === BỎ XỬ LÝ UPLOAD ===
        /*
        if ($request->hasFile('Anh')) { ... }
        */

        Slider::create($data);

        return redirect()->route('admin.slider.index')->with('success', 'Thêm slider thành công!');
    }

    public function show(Slider $slider)
    {
        $slider->load('phim'); // Tải phim liên quan
        return view('admin.slider.show', compact('slider'));
    }

    public function edit(Slider $slider)
    {
        $phims = Phim::where('TrangThai', 1)->orderBy('TenPhim')->get();
        $viTris = Slider::distinct()->pluck('ViTri')->toArray();
        return view('admin.slider.edit', compact('slider', 'phims', 'viTris'));
    }

    public function update(Request $request, Slider $slider)
    {
        // === SỬA VALIDATION ===
        $data = $request->validate([
            'TenSlider' => 'required|string|max:255',
            'URL' => 'nullable|url|max:255',
            'MaPhim' => 'nullable|exists:phim,MaPhim',
            // Ảnh không bắt buộc khi update, phải là URL
            'Anh' => 'nullable|url|max:1000', 
            'SapXep' => 'nullable|integer',
            'ViTri' => 'required|string|max:255',
            'MoTa' => 'required|string|max:255',
            'TuKhoa' => 'required|string|max:255',
            'TenChuDe' => 'required|string|max:255',
            'TrangThai' => 'nullable'
        ]);

        $data['NguoiCapNhat'] = Auth::Id() ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;
        $data['SapXep'] = $data['SapXep'] ?? 0;

        // === BỎ XỬ LÝ UPLOAD VÀ XÓA FILE CŨ ===
        /*
        if ($request->hasFile('Anh')) { ... Storage::disk('public')->delete(...); ... }
        */

        // Xử lý URL rỗng
        if (empty($data['Anh'])) {
            unset($data['Anh']); 
        }

        $slider->update($data);

        return redirect()->route('admin.slider.index')->with('success', 'Cập nhật slider thành công!');
    }

    public function destroy(Slider $slider)
    {
        $slider->delete();
        return redirect()->route('admin.slider.index')->with('success', 'Xóa slider thành công!');
    }
}