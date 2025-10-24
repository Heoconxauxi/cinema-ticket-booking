<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\TaiKhoan;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function loginUser(Request $request)
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

        // Kiểm tra quyền User (Giả sử quyền user là 0)
        // Nếu bạn dùng hằng số như tôi gợi ý: if ($user->Quyen != TaiKhoan::ROLE_USER)
        if ($user->Quyen != 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản không có quyền đăng nhập người dùng.'
            ], 403); // 403 Forbidden
        }

        // Tạo token
        // Gán ability 'role:user' để phân biệt với admin
        $token = $user->createToken('user-token', ['role:user'])->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Đăng nhập thành công!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->load('nguoidung')
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenDangNhap' => 'required|string|max:50|unique:taikhoan,TenDangNhap',
            'MatKhau' => 'required|string|min:6|max:100',
            'TenND' => 'required|string|max:200',
            'Quyen' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $taiKhoan = TaiKhoan::create([
                'TenDangNhap' => $request->TenDangNhap,
                'MatKhau' => Hash::make($request->MatKhau),
                'TenND' => $request->TenND,
                'Quyen' => $request->Quyen,
            ]);

            NguoiDung::create([
                'MaND' => $taiKhoan->MaND,
                'TenND' => $request->TenND,
                'NguoiTao' => $request->NguoiTao ?? 0,
                'NgayTao' => now(),
                'TrangThai' => 1,
                'Email' => $request->Email ?? null,
            ]);

            DB::commit(); 
            
            $taiKhoan->load('nguoidung');
            
            return response()->json([
                'success' => true,
                'message' => 'Tạo tài khoản thành công!',
                'data' => $taiKhoan
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json([
                'success' => false,
                'message' => 'Tạo tài khoản thất bại. Lỗi: ' . $e->getMessage()
            ], 500);
        }
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
