<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    // 🟢 Gọi API để đăng ký tài khoản và người dùng
    public function register(Request $request)
    {
        $request->validate([
            'TenDangNhap' => 'required|string|max:50',
            'TenND' => 'required|string|max:200',
            'MatKhau' => 'required|string|min:6|max:100',
        ]);

        try {
            // Gửi dữ liệu đến API Laravel chạy ở cổng 8000
            $response = Http::post('http://127.0.0.1:8000/api/register', [
                'TenDangNhap' => $request->TenDangNhap,
                'TenND' => $request->TenND,
                'MatKhau' => $request->MatKhau,
                'Quyen' => 0, // 0 = user thường
                'NguoiTao' => 1,
            ]);

            // Giải mã phản hồi
            $data = $response->json();

            if ($response->failed() || !$data['success']) {
                return back()->withErrors([
                    'api' => $data['message'] ?? 'Không thể tạo tài khoản (API lỗi)!'
                ])->withInput();
            }

            // Thành công
            return redirect()->route('login')->with('success', 'Tạo tài khoản thành công! Bạn có thể đăng nhập ngay.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'api' => 'Lỗi khi gọi API: ' . $e->getMessage()
            ])->withInput();
        }
    }

   // 🟢 Đăng nhập: Gọi API mới `/api/login`
    public function login(Request $request)
    {
        $request->validate([
            'TenDangNhap' => 'required|string',
            'MatKhau' => 'required|string',
        ]);

        try {
            $response = Http::post('http://127.0.0.1:8000/api/login', [
                'TenDangNhap' => $request->TenDangNhap,
                'MatKhau' => $request->MatKhau,
            ]);

            $data = $response->json();

            // ✅ Kiểm tra lỗi từ API
            if ($response->failed() || empty($data['success']) || !$data['success']) {
                return back()->with('error', $data['message'] ?? 'Đăng nhập thất bại.')->withInput();
            }

            // ✅ Nếu API trả về thành công
            $user = $data['user'] ?? null;

            if (!$user) {
                return back()->with('error', 'Không nhận được thông tin người dùng từ API.')->withInput();
            }

            // ✅ Lưu session thủ công
            session([
                'NDloggedIn' => true,
                'MaND' => $user['MaND'] ?? null,
                'TenND' => $user['TenND'] ?? $user['TenDangNhap'],
                'Quyen' => $user['Quyen'] ?? 0,
                'access_token' => $data['access_token'] ?? null,
            ]);

            return redirect()->route('index')->with('success', 'Đăng nhập thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi khi gọi API: ' . $e->getMessage())->withInput();
        }
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login')->with('success', 'Đăng xuất thành công!');
    }

    public function index(Request $request)
    {
        if (!$request->session()->has('NDloggedIn')) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để truy cập!');
        }

        return view('index');
    }
}
