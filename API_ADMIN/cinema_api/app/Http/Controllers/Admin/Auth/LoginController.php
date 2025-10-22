<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Hiển thị form đăng nhập.
     */
    public function showLoginForm()
    {
        // Nếu đã đăng nhập và là admin, chuyển thẳng vào dashboard
        if (Auth::check() && (Auth::user()->Quyen == 1 || Auth::user()->Quyen == 2)) {
            return redirect()->route('admin.home');
        }
        return view('admin.auth.login');
    }

    /**
     * Xử lý yêu cầu đăng nhập.
     */
    public function login(Request $request)
    {
        // 1. Validate dữ liệu
        $credentials = $request->validate([
            'TenDangNhap' => 'required|string',
            'MatKhau' => 'required|string',
        ]);

        $loginCredentials = [
            'TenDangNhap' => $credentials['TenDangNhap'],
            'password' => $credentials['MatKhau'] // Auth::attempt() yêu cầu key 'password'
        ];

        // 2. SỬA LỖI 1: Đảo ngược logic cho đúng
        // Nếu Auth::attempt() trả về false (thất bại)
        if (!Auth::attempt($loginCredentials)) {
            // Thì mới báo lỗi
            throw ValidationException::withMessages([
                'TenDangNhap' => 'Tên đăng nhập hoặc mật khẩu không chính xác.',
            ]);
        }
        
        // --- Nếu code chạy đến đây, có nghĩa là ĐĂNG NHẬP ĐÃ THÀNH CÔNG ---

        // 3. Lấy thông tin user
        $user = Auth::user();

        // 4. KIỂM TRA QUYỀN
        // Quyen = 1 (Admin) hoặc 2 (Nhân viên)
        if ($user->Quyen != 1 && $user->Quyen != 2) {
            Auth::logout(); // Đăng xuất họ ra vì không có quyền
            throw ValidationException::withMessages([
                'TenDangNhap' => 'Bạn không có quyền truy cập vào trang quản trị.',
            ]);
        }

        // 5. Tạo lại session và chuyển hướng
        $request->session()->regenerate();
        
        
        // 6. SỬA LỖI 2: Dùng redirect()->route() để luôn đi đến dashboard
        return redirect()->route('admin.home');
    }

    /**
     * Xử lý yêu cầu đăng xuất.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('admin.login.form'));
    }
}
