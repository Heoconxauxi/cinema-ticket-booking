<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckLogin
{
    public function handle(Request $request, Closure $next)
    {
        // Nếu chưa đăng nhập → quay lại login
        if (!Session::has('NDloggedIn')) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập trước khi truy cập trang này!');
        }

        // Nếu đã đăng nhập → cho phép đi tiếp
        return $next($request);
    }
}
