<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    // Định nghĩa sẵn các kiểu và vị trí để dùng trong form
    private $kieuMenus = ['Custom' => 'Tùy chỉnh (Nhập URL)', 'Category' => 'Danh mục (Chưa hỗ trợ)', 'Post' => 'Bài viết (Chưa hỗ trợ)'];
    private $viTris = ['header' => 'Header', 'footer' => 'Footer'];

    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        $search = $request->input('searchString', '');

        $query = Menu::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('TenMenu', 'like', '%' . $search . '%')
                  ->orWhere('ViTri', 'like', '%' . $search . '%');
            });
        }

        // Sắp xếp theo Vị trí -> Thứ tự -> Ngày tạo
        $list_menu = $query->orderBy('ViTri', 'asc')
                           ->orderBy('Order', 'asc')
                           ->orderBy('NgayTao', 'desc')
                           ->paginate($per_page);

        return view('admin.menu.index', compact('list_menu', 'per_page', 'search'));
    }

    public function create()
    {
        $kieuMenus = $this->kieuMenus;
        $viTris = $this->viTris;
        return view('admin.menu.create', compact('kieuMenus', 'viTris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'TenMenu' => 'required|string|max:255',
            'KieuMenu' => ['required', Rule::in(array_keys($this->kieuMenus))],
            'ViTri' => ['required', Rule::in(array_keys($this->viTris))],
            'LienKet' => 'nullable|required_if:KieuMenu,Custom|string|max:255', // Bắt buộc nếu là Custom
            'TableId' => 'nullable|integer', // Tạm thời chưa dùng
            'Order' => 'nullable|integer',
            'TrangThai' => 'nullable'
        ]);

        $data['NguoiTao'] = auth()->id ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;
        $data['Order'] = $data['Order'] ?? 0;

        // Xử lý TableId và LienKet dựa trên KieuMenu (Tạm thời chỉ hỗ trợ Custom)
        if ($data['KieuMenu'] !== 'Custom') {
            $data['LienKet'] = null; // Hoặc logic khác nếu bạn hỗ trợ Category/Post
        } else {
            $data['TableId'] = null;
        }

        Menu::create($data);

        return redirect()->route('admin.menu.index')->with('success', 'Thêm menu thành công!');
    }

    public function edit(Menu $menu)
    {
        $kieuMenus = $this->kieuMenus;
        $viTris = $this->viTris;
        return view('admin.menu.edit', compact('menu', 'kieuMenus', 'viTris'));
    }

    public function update(Request $request, Menu $menu)
    {
         $data = $request->validate([
            'TenMenu' => 'required|string|max:255',
            'KieuMenu' => ['required', Rule::in(array_keys($this->kieuMenus))],
            'ViTri' => ['required', Rule::in(array_keys($this->viTris))],
            'LienKet' => 'nullable|required_if:KieuMenu,Custom|string|max:255',
            'TableId' => 'nullable|integer',
            'Order' => 'nullable|integer',
            'TrangThai' => 'nullable'
        ]);

        $data['NguoiCapNhat'] = auth()->id ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;
        $data['Order'] = $data['Order'] ?? 0;

        if ($data['KieuMenu'] !== 'Custom') {
            $data['LienKet'] = null; 
        } else {
            $data['TableId'] = null;
        }
        
        $menu->update($data);

        return redirect()->route('admin.menu.index')->with('success', 'Cập nhật menu thành công!');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Xóa menu thành công!');
    }
}