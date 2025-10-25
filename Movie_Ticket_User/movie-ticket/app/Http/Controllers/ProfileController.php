<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProfileController extends Controller
{
    // 📌 Hiển thị trang hồ sơ
    public function index(Request $request)
    {
        $MaND = session('MaND'); // lấy từ session sau login
        $apiBase = 'http://127.0.0.1:8000/api';

        if (!$MaND) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập trước.');
        }

        // Gọi API lấy thông tin tài khoản
        $response = Http::get("$apiBase/taikhoan/$MaND");

        if ($response->failed()) {
            return back()->with('error', 'Không thể kết nối API.');
        }

        $data = $response->json();

        if (empty($data['success']) || !$data['success']) {
            return back()->with('error', $data['message'] ?? 'Không lấy được thông tin người dùng.');
        }

        return view('profile', [
            'user' => $data['data'],
            'apiBase' => $apiBase,
            'MaND' => $MaND
        ]);
    }

    // 📌 Cập nhật hồ sơ
    public function update(Request $request)
    {
        $MaND = session('MaND');
        $apiBase = 'http://127.0.0.1:8000/api';

        if (!$MaND) {
            return redirect()->route('login')->with('error', 'Không xác định được người dùng.');
        }

        $payload = [
            'TenDangNhap' => $request->TenDangNhap,
            'TenND' => $request->TenND,
            'Email' => $request->Email,
            'SDT' => $request->SDT,
            'NgaySinh' => $request->NgaySinh,
            'Anh' => $request->Anh,
            'TrangThai' => $request->TrangThai,
            'Quyen' => $request->Quyen ?? 0,
        ];

        if (!empty($request->MatKhau)) {
            $payload['MatKhau'] = $request->MatKhau;
        }

        try {
            $response = Http::put("$apiBase/taikhoan/$MaND", $payload);

            if ($response->failed()) {
                return back()->with('error', 'API lỗi: ' . $response->status());
            }

            $data = $response->json();

            if (isset($data['success']) && $data['success'] === true) {
                return redirect()->back()->with('success', $data['message'] ?? 'Cập nhật thành công!');
            }

            return back()->with('error', $data['message'] ?? 'Cập nhật thất bại.');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi gọi API: ' . $e->getMessage());
        }
    }
}
