<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Session;

class TaiKhoanApiController extends Controller
{
    public function index()
    {
        // kiểm tra session token
        if (!Session::has('api_token')) {
            return response()->json([
                'success' => false,
                'message' => 'Chưa đăng nhập hoặc token hết hạn!'
            ], 401);
        }

        $taikhoan = TaiKhoan::with(['nguoidung'])->get(['MaND', 'TenDangNhap', 'TenND', 'Quyen']);

        return response()->json([
            'success' => true,
            'data' => $taikhoan
        ]);
    }
}
