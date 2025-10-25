<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthApiController extends Controller
{
    // API đăng nhập
    public function login(Request $request)
    {
        $request->validate([
            'TenDangNhap' => 'required',
            'MatKhau' => 'required'
        ]);

        $user = TaiKhoan::where('TenDangNhap', $request->TenDangNhap)->first();

        if ($user && Hash::check($request->MatKhau, $user->MatKhau)) {
            // tạo token ngẫu nhiên
            $token = bin2hex(random_bytes(32));

            // lưu session (để kiểm tra sau này)
            Session::put('api_token', $token);
            Session::put('user_id', $user->MaND);

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công!',
                'token' => $token,
                'user' => [
                    'MaND' => $user->MaND,
                    'TenND' => $user->TenND,
                    'Quyen' => $user->Quyen
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Sai tên đăng nhập hoặc mật khẩu!']);
    }

    // API đăng xuất
    public function logout()
    {
        Session::forget(['api_token', 'user_id']);
        return response()->json(['success' => true, 'message' => 'Đã đăng xuất']);
    }
}
