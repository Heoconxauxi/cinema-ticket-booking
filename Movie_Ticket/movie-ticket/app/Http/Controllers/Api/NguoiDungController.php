<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NguoiDungController extends Controller
{
    /**
     * Lấy thông tin người dùng hiện tại (theo session đăng nhập)
     * Gọi API backend ở port 8000
     */
    public function getCurrentUser(Request $request)
    {
        // ✅ Lấy mã người dùng từ session
        $MaND = session('MaND');
        $apiBase = 'http://127.0.0.1:8000/api';

        if (!$MaND) {
            return response()->json([
                'success' => false,
                'message' => 'Chưa đăng nhập!'
            ], 401);
        }

        try {
            // ✅ Gọi API backend để lấy thông tin người dùng
            $response = Http::get("$apiBase/nguoidung/$MaND");

            if ($response->failed()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể kết nối API người dùng.'
                ], 500);
            }

            $data = $response->json();

            if (empty($data['success']) || !$data['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $data['message'] ?? 'Không lấy được thông tin người dùng.'
                ], 404);
            }

            // ✅ Lưu avatar vào session (phục vụ header.blade.php)
            $userData = $data['data'];
            session(['AnhND' => $userData['Anh'] ?? null]);

            return response()->json([
                'success' => true,
                'message' => 'Lấy thông tin người dùng thành công.',
                'data' => $userData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi gọi API: ' . $e->getMessage()
            ], 500);
        }
    }
}
