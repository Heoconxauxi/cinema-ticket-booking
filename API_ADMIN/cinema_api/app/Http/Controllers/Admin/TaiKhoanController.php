<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use App\Models\NguoiDung; // <-- Import NguoiDung
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // <-- Import DB for transaction
use Illuminate\Support\Facades\Auth; // <-- Import Auth

class TaiKhoanController extends Controller
{
    private $roles = [0 => 'Người dùng', 1 => 'Admin', 2 => 'Nhân viên'];

    public function index(Request $request)
    {
        // ... (code cũ không đổi) ...
        $per_page = $request->input('per_page', 10);
        $search = $request->input('searchString', '');

        $query = TaiKhoan::query();

        if ($search) {
           $query->where(function ($q) use ($search) {
               $q->where('TenDangNhap', 'like', '%' . $search . '%')
                 ->orWhere('TenND', 'like', '%' . $search . '%');
           });
        }

        $list_taikhoan = $query->orderBy('MaND', 'asc')->paginate($per_page);

        $roles = $this->roles;
        
        return view('admin.taikhoan.index', compact('list_taikhoan', 'per_page', 'search', 'roles')); // Truyền roles ra view index
    }

    public function create()
    {
        // Bây giờ form này sẽ tạo cả người dùng, nên không còn warning
        $roles = $this->roles;
        return view('admin.taikhoan.create', compact('roles'));
    }

    // ===== PHƯƠNG THỨC STORE ĐƯỢC CẬP NHẬT =====
    public function store(Request $request)
    {
        // 1. Validate dữ liệu cho cả TaiKhoan và NguoiDung (tối thiểu)
        $data = $request->validate([
            // TaiKhoan fields
            'TenDangNhap' => 'required|string|max:50|unique:taikhoan,TenDangNhap',
            'MatKhau' => 'required|string|min:6|confirmed',
            'TenND' => 'required|string|max:200', // Tên người dùng là bắt buộc
            'Quyen' => ['required', 'integer', Rule::in(array_keys($this->roles))],
            // NguoiDung fields (có thể thêm các trường khác nếu muốn nhập ngay từ đầu)
             'Email' => 'nullable|email|max:255',
             'SDT' => 'nullable|string|max:10',
             'TrangThai' => 'nullable', // Trang thai cho NguoiDung
        ]);

        // 2. Sử dụng Transaction để đảm bảo an toàn
        DB::beginTransaction();
        try {
            // 2.1. Tạo TaiKhoan trước
            $taiKhoan = TaiKhoan::create([
                'TenDangNhap' => $data['TenDangNhap'],
                'MatKhau' => Hash::make($data['MatKhau']),
                'TenND' => $data['TenND'], // Tên người dùng lưu ở cả 2 bảng theo cấu trúc DB của bạn
                'Quyen' => $data['Quyen'],
            ]);

            // 2.2. Lấy MaND vừa tạo
            $newMaND = $taiKhoan->MaND;

            // 2.3. Tạo NguoiDung tương ứng
            NguoiDung::create([
                'MaND' => $newMaND, // Liên kết với TaiKhoan
                'TenND' => $data['TenND'],
                'Email' => $data['Email'] ?? null, // Lấy Email nếu có
                'SDT' => $data['SDT'] ?? null,     // Lấy SDT nếu có
                'NguoiTao' => Auth::id() ?? 0,    // ID của admin đang tạo
                'NgayTao' => now(),             // Ngày giờ hiện tại
                'TrangThai' => $request->has('TrangThai') ? 1 : 0, // Trạng thái của người dùng
                // Các trường khác như NgaySinh, GioiTinh, Anh sẽ là null ban đầu
            ]);

            // 3. Nếu mọi thứ thành công, commit transaction
            DB::commit();

            return redirect()->route('admin.taikhoan.index')->with('success', 'Thêm tài khoản và hồ sơ người dùng thành công!');

        } catch (\Exception $e) {
            // 4. Nếu có lỗi, rollback transaction
            DB::rollBack();
            // Ghi log lỗi để debug (quan trọng)
            // \Log::error('Lỗi khi tạo tài khoản và người dùng: ' . $e->getMessage());

            // Thông báo lỗi cho người dùng
            return back()->withInput()->with('error', 'Đã xảy ra lỗi. Không thể thêm tài khoản và người dùng.');
        }
    }
    // ===== KẾT THÚC PHƯƠNG THỨC STORE =====


    public function edit(TaiKhoan $taikhoan)
    {
        $roles = $this->roles;
        return view('admin.taikhoan.edit', compact('taikhoan', 'roles'));
    }

    public function update(Request $request, TaiKhoan $taikhoan)
    {
         $data = $request->validate([
            'TenDangNhap' => ['required', 'string', 'max:50', Rule::unique('taikhoan')->ignore($taikhoan->MaND, 'MaND')],
            'MatKhau' => 'nullable|string|min:6|confirmed',
            'TenND' => 'required|string|max:200',
            'Quyen' => ['required', 'integer', Rule::in(array_keys($this->roles))],
        ]);

        DB::beginTransaction(); // Dùng transaction cho an toàn
        try {
            $updateData = [
                'TenDangNhap' => $data['TenDangNhap'],
                'TenND' => $data['TenND'],
                'Quyen' => $data['Quyen'],
            ];

            if (!empty($data['MatKhau'])) {
                $updateData['MatKhau'] = Hash::make($data['MatKhau']);
            }

            // Cập nhật TaiKhoan
            $taikhoan->update($updateData);

            // Cập nhật tên trong NguoiDung nếu tồn tại
            $nguoiDung = $taikhoan->nguoiDung; // Sử dụng quan hệ đã định nghĩa
            if ($nguoiDung) {
                $nguoiDung->update(['TenND' => $data['TenND']]);
            }

            DB::commit();
             return redirect()->route('admin.taikhoan.index')->with('success', 'Cập nhật tài khoản thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            // \Log::error('Lỗi khi cập nhật tài khoản: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Đã xảy ra lỗi khi cập nhật tài khoản.');
        }
    }

    public function destroy(TaiKhoan $taikhoan)
    {
        // ... (code cũ không đổi, ON DELETE CASCADE sẽ xử lý NguoiDung) ...
        if ($taikhoan->MaND === 3 || $taikhoan->MaND === Auth::id()) {
             return back()->with('error', 'Không thể xóa tài khoản Admin chính hoặc tài khoản đang đăng nhập!');
        }
        
        try {
             $taikhoan->delete(); // ON DELETE CASCADE sẽ xóa NguoiDung
             return redirect()->route('admin.taikhoan.index')->with('success', 'Xóa tài khoản thành công! (Hồ sơ người dùng liên kết cũng đã bị xóa)');
        } catch (\Exception $e) {
             // \Log::error('Lỗi khi xóa tài khoản: ' . $e->getMessage());
             return back()->with('error', 'Đã xảy ra lỗi khi xóa tài khoản.');
        }
    }
}