<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ThamSo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ThamSoController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->input('per_page',10);
        $search = $request->input('searchString', '');

        $query = ThamSo::query();

        if ($search) {
            // Tìm theo Tên Tham Số
            $query->where('TenThamSo', 'like', '%' . $search . '%');
        }

        $list_thamso = $query->orderBy('TenThamSo', 'asc')->paginate($per_page);

        return view('admin.thamso.index', compact('list_thamso', 'per_page', 'search'));
    }

    public function create()
    {
        return view('admin.thamso.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // Tên tham số là duy nhất
            'TenThamSo' => 'required|string|max:255|unique:thamso,TenThamSo', 
            'DonViTinh' => 'nullable|string|max:255',
            'GiaTri' => 'required|string|max:255', // Giá trị có thể là số hoặc chữ
            'TrangThai' => 'nullable'
        ]);

        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;

        ThamSo::create($data);

        return redirect()->route('admin.thamso.index')->with('success', 'Thêm tham số thành công!');
    }

    // Phương thức show() không cần thiết vì đã loại trừ khỏi route

    public function edit(ThamSo $thamso)
    {
        return view('admin.thamso.edit', compact('thamso'));
    }

    public function update(Request $request, ThamSo $thamso)
    {
        $data = $request->validate([
            'TenThamSo' => [
                'required', 'string', 'max:255',
                // Rule unique, bỏ qua ID hiện tại
                Rule::unique('thamso')->ignore($thamso->Id, 'Id') 
            ],
            'DonViTinh' => 'nullable|string|max:255',
            'GiaTri' => 'required|string|max:255',
            'TrangThai' => 'nullable'
        ]);

        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;

        $thamso->update($data);

        return redirect()->route('admin.thamso.index')->with('success', 'Cập nhật tham số thành công!');
    }

    public function destroy(ThamSo $thamso)
    {
        // Kiểm tra xem tham số có đang được dùng ở đâu không (ví dụ: giá ghế)
        // Nếu bạn muốn ngăn xóa các tham số quan trọng
        // if (in_array($thamso->TenThamSo, ['Đơn', 'Đôi', 'VIP'])) {
        //     return back()->with('error', 'Không thể xóa tham số giá ghế mặc định!');
        // }
        
        $thamso->delete();
        return redirect()->route('admin.thamso.index')->with('success', 'Xóa tham số thành công!');
    }
}