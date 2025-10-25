<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session; // Thêm Session facade

class VeController extends Controller // <-- Đã đổi tên class
{
    private $apiUrl = 'http://127.0.0.1:8000/api'; // URL API của bạn

    /**
     * Hiển thị danh sách vé (hóa đơn) của người dùng.
     */
    public function index()
    {
        // 1. Kiểm tra đăng nhập
        if (!Session::get('NDloggedIn')) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem vé đã đặt.');
        }

        // 2. Lấy MaND từ Session
        $maND = Session::get('MaND');
        if (!$maND) {
             // Trường hợp hiếm gặp: đã đăng nhập nhưng không có MaND trong session
             return redirect('/')->with('error', 'Lỗi: Không tìm thấy thông tin người dùng.');
        }

        // 3. Gọi API lấy danh sách hóa đơn CỦA NGƯỜI DÙNG NÀY
        try {
            $response = Http::get("{$this->apiUrl}/hoadon", [
                'MaND' => $maND
            ]);

            if (!$response->successful()) {
                throw new \Exception('Không thể kết nối đến API.');
            }

            $apiData = $response->json();
            if (empty($apiData['success'])) {
                 throw new \Exception('API trả về lỗi: ' . ($apiData['message'] ?? 'Unknown error'));
            }

            $hoaDons = $apiData['data'] ?? [];

        } catch (\Exception $e) {
            // Xử lý lỗi nếu không gọi được API
            return view('tickets.index', ['hoaDons' => []])->with('error', 'Lỗi khi tải danh sách vé: ' . $e->getMessage());
        }

        // 4. Trả về view với danh sách hóa đơn
        return view('tickets.index', ['hoaDons' => $hoaDons]);
    }

    /**
     * Hiển thị chi tiết một vé (hóa đơn).
     */
    public function show($id) // $id ở đây là MaHD
    {
        // 1. Kiểm tra đăng nhập
        if (!Session::get('NDloggedIn')) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem chi tiết vé.');
        }
         $maND = Session::get('MaND'); // Lấy MaND để kiểm tra quyền sở hữu

        // 2. Gọi API lấy chi tiết hóa đơn
        try {
            $response = Http::get("{$this->apiUrl}/hoadon/{$id}"); // API show đã có sẵn

             if (!$response->successful()) {
                if ($response->status() == 404) {
                     abort(404, 'Không tìm thấy hóa đơn này.');
                }
                throw new \Exception('Không thể kết nối đến API.');
            }

            $apiData = $response->json();
             if (empty($apiData['success']) || empty($apiData['data'])) {
                 abort(404, 'Không tìm thấy hóa đơn này.');
            }

            $hoaDon = $apiData['data'];

             // 3. KIỂM TRA QUYỀN SỞ HỮU (Quan trọng)
             if ($hoaDon['MaND'] != $maND) {
                  abort(403, 'Bạn không có quyền xem hóa đơn này.');
             }

        } catch (\Exception $e) {
            return redirect()->route('tickets.index')->with('error', 'Lỗi khi tải chi tiết vé: ' . $e->getMessage());
        }

        // 4. Trả về view với chi tiết hóa đơn
        return view('tickets.show', ['hoaDon' => $hoaDon]);
    }
}