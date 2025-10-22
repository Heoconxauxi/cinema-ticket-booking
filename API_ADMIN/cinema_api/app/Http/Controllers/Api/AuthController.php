<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenDangNhap' => 'required|string',
            'MatKhau' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Tìm user theo tên đăng nhập
        $user = TaiKhoan::where('TenDangNhap', $request->TenDangNhap)->first();

        // Kiểm tra tồn tại và mật khẩu
        if (!$user || !Hash::check($request->MatKhau, $user->MatKhau)) {
            return response()->json([
                'success' => false,
                'message' => 'Tên đăng nhập hoặc mật khẩu không chính xác.'
            ], 401);
        }

        // Kiểm tra quyền Admin
        if ($user->Quyen != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền truy cập trang quản trị.'
            ], 403);
        }

        // Tạo token
        $token = $user->createToken('admin-token', ['role:admin'])->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->load('nguoidung')
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công!'
        ]);
    }
}
