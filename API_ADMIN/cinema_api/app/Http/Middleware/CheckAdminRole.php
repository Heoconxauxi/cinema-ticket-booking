<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next)
    {
        // 1. Kiểm tra xem người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->route('admin.login.form');
        }

        // 2. Lấy thông tin người dùng
        $user = Auth::user();

        // 3. Kiểm tra Quyen (1 = Admin, 2 = NhanVien)
        if ($user->Quyen == 1 || $user->Quyen == 2) {
            // Nếu đúng, cho phép tiếp tục
            return $next($request);
        }

        // 4. Nếu không phải Admin/NV, đăng xuất và đẩy về trang login
        Auth::logout();
        return redirect()->route('admin.login.form')->withErrors([
            'TenDangNhap' => 'Bạn không có quyền truy cập.'
        ]);
    }
}
