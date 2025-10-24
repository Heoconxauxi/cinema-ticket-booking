<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // For password hashing
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NguoiDungController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        $search = $request->input('searchString', '');

        // Bảng quyền giống TaiKhoanController
        $roles = [0 => 'Người dùng', 1 => 'Admin', 2 => 'Nhân viên'];

        // JOIN với bảng taikhoan để lấy thêm TenDangNhap và Quyen
        $query = NguoiDung::join('taikhoan', 'nguoidung.MaND', '=', 'taikhoan.MaND')
            ->select('nguoidung.*', 'taikhoan.TenDangNhap', 'taikhoan.Quyen');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nguoidung.TenND', 'like', '%' . $search . '%')
                ->orWhere('nguoidung.Email', 'like', '%' . $search . '%')
                ->orWhere('nguoidung.SDT', 'like', '%' . $search . '%')
                ->orWhere('taikhoan.TenDangNhap', 'like', '%' . $search . '%');
            });
        }

        $list_nguoidung = $query->orderBy('nguoidung.MaND', 'asc')->paginate($per_page);

        return view('admin.nguoidung.index', compact('list_nguoidung', 'per_page', 'search', 'roles'));
    }


    public function create()
    {
        // ... (Giữ nguyên)
        $roles = [0 => 'Người dùng', 1 => 'Admin', 2 => 'Nhân viên'];
        return view('admin.nguoidung.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // === SỬA VALIDATION ===
        $data = $request->validate([
            // TaiKhoan fields
            'TenDangNhap' => 'required|string|max:50|unique:taikhoan,TenDangNhap',
            'MatKhau' => 'required|string|min:6|confirmed', 
            'Quyen' => 'required|integer|in:0,1,2',
            // NguoiDung fields
            'TenND' => 'required|string|max:255',
            'Email' => 'nullable|email|max:255',
            'NgaySinh' => 'nullable|date',
            'GioiTinh' => 'nullable|boolean',
            'SDT' => 'nullable|string|max:10',
            // Đổi validation ảnh
            'Anh' => 'nullable|url|max:1000', 
            'TrangThai' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            // 1. Create TaiKhoan (giữ nguyên)
            $taiKhoan = TaiKhoan::create([
                'TenDangNhap' => $data['TenDangNhap'],
                'MatKhau' => Hash::make($data['MatKhau']), 
                'TenND' => $data['TenND'],
                'Quyen' => $data['Quyen'],
            ]);

            // 2. Prepare NguoiDung data
            $nguoiDungData = [
                'MaND' => $taiKhoan->MaND, 
                'TenND' => $data['TenND'],
                'Email' => $data['Email'],
                'NgaySinh' => $data['NgaySinh'],
                'GioiTinh' => $data['GioiTinh'] ?? null, 
                'SDT' => $data['SDT'],
                'NguoiTao' => Auth::Id() ?? 0,
                'TrangThai' => $request->has('TrangThai') ? 1 : 0,
                 // Gán trực tiếp URL ảnh
                'Anh' => $data['Anh'] ?? null, 
            ];

            // === BỎ XỬ LÝ UPLOAD ===
            /*
            if ($request->hasFile('Anh')) { ... }
            */

            // 3. Create NguoiDung
            NguoiDung::create($nguoiDungData);

            DB::commit(); 
            return redirect()->route('admin.nguoidung.index')->with('success', 'Thêm người dùng thành công!');

        } catch (\Exception $e) {
            DB::rollBack(); 
            // Log::error($e->getMessage());
            return back()->withInput()->with('error', 'Đã xảy ra lỗi khi thêm người dùng.');
        }
    }


    public function show(NguoiDung $nguoidung)
    {
        // ... (Giữ nguyên, vì trang show CẦN load taiKhoan)
        $nguoidung->load('taiKhoan'); 
        $roles = [0 => 'Người dùng', 1 => 'Admin', 2 => 'Nhân viên'];
        $roleName = $roles[$nguoidung->taiKhoan->Quyen] ?? 'Không xác định';
        return view('admin.nguoidung.show', compact('nguoidung', 'roleName'));
    }

    public function edit(NguoiDung $nguoidung)
    {
        // ... (Giữ nguyên, vì trang edit CẦN load taiKhoan)
        $nguoidung->load('taiKhoan'); 
        $roles = [0 => 'Người dùng', 1 => 'Admin', 2 => 'Nhân viên'];
        return view('admin.nguoidung.edit', compact('nguoidung', 'roles'));
    }

    public function update(Request $request, NguoiDung $nguoidung)
    {
        $taiKhoan = $nguoidung->taiKhoan; 
        if (!$taiKhoan) { return back()->with('error', 'Lỗi: Không tìm thấy tài khoản liên kết.'); }

        // === SỬA VALIDATION ===
        $data = $request->validate([
            'TenDangNhap' => ['required', 'string', 'max:50', Rule::unique('taikhoan')->ignore($taiKhoan->MaND, 'MaND')],
            'MatKhau' => 'nullable|string|min:6|confirmed', 
            'Quyen' => 'required|integer|in:0,1,2',
            'TenND' => 'required|string|max:255',
            'Email' => 'nullable|email|max:255',
            'NgaySinh' => 'nullable|date',
            'GioiTinh' => 'nullable|boolean',
            'SDT' => 'nullable|string|max:10',
            // Đổi validation ảnh
            'Anh' => 'nullable|url|max:1000', 
            'TrangThai' => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            // 1. Update TaiKhoan (giữ nguyên)
            $taiKhoanData = [
                'TenDangNhap' => $data['TenDangNhap'],
                'TenND' => $data['TenND'], 
                'Quyen' => $data['Quyen'],
            ];
            if (!empty($data['MatKhau'])) { $taiKhoanData['MatKhau'] = Hash::make($data['MatKhau']); }
            $taiKhoan->update($taiKhoanData);

            // 2. Prepare NguoiDung data
            $nguoiDungData = [
                'TenND' => $data['TenND'],
                'Email' => $data['Email'],
                'NgaySinh' => $data['NgaySinh'],
                'GioiTinh' => $data['GioiTinh'] ?? null,
                'SDT' => $data['SDT'],
                'NguoiCapNhat' => Auth::Id() ?? 0,
                'TrangThai' => $request->has('TrangThai') ? 1 : 0,
            ];

            // Xử lý URL ảnh mới
             if (array_key_exists('Anh', $data)) { // Chỉ xử lý nếu 'Anh' có trong $data (tức là được validate)
                if (empty($data['Anh'])) {
                    // Nếu người dùng xóa URL, bạn có thể muốn đặt là null hoặc giữ nguyên
                    $nguoiDungData['Anh'] = null; // Đặt là null nếu muốn xóa ảnh
                     // unset($nguoiDungData['Anh']); // Bỏ dòng này nếu muốn giữ ảnh cũ khi input rỗng
                } else {
                    $nguoiDungData['Anh'] = $data['Anh']; // Gán URL mới
                }
            }


            // 3. Update NguoiDung
            $nguoidung->update($nguoiDungData);

            DB::commit();
            return redirect()->route('admin.nguoidung.index')->with('success', 'Cập nhật người dùng thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error($e->getMessage());
            return back()->withInput()->with('error', 'Đã xảy ra lỗi khi cập nhật người dùng.');
        }
    }


    public function destroy(NguoiDung $nguoidung)
    {
        $nguoidung->delete(); // The trigger will delete the associated TaiKhoan

        return redirect()->route('admin.nguoidung.index')->with('success', 'Xóa người dùng thành công!');
    }
}