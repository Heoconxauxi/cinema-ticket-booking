<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log; // Thêm Log facade

class HoaDonController extends Controller // Đổi tên class để phù hợp hơn với chức năng
{
    private $apiUrl = 'http://127.0.0.1:8000/api'; // URL API của bạn

    /**
     * LƯU hóa đơn (sau khi thanh toán thành công).
     * Được gọi bởi POST /hoadon từ frontend (AJAX).
     */
    public function store(Request $request)
    {
        // 1. Kiểm tra đăng nhập và dữ liệu cơ bản
        if (!Session::get('NDloggedIn') || !$request->filled('MaND')) {
            return response()->json([
                'success' => false, 
                'message' => 'Lỗi xác thực người dùng hoặc thiếu MaND.'
            ], 401); // Unauthorized
        }

        // 2. Chuẩn bị dữ liệu gửi đi
        $maND = Session::get('MaND');
        $hoaDonData = [
            'MaND' => $maND,
            // Đảm bảo các trường này khớp với yêu cầu API của bạn
            'TongTien' => $request->input('TongTien'), 
            'NguoiTao' => $maND,
            'details' => $request->input('details'), // Mảng chi tiết vé
            // Thêm trường payment_status hoặc payment_intent_id nếu cần theo dõi
            // 'payment_status' => 'paid',
        ];

        // 3. Gọi API lưu hóa đơn
        try {
            $response = Http::post("{$this->apiUrl}/hoadon", $hoaDonData);

            if (!$response->successful()) {
                // Log lỗi chi tiết từ API
                Log::error('API Lưu Hóa Đơn Lỗi: ' . $response->status(), [
                    'request_data' => $hoaDonData,
                    'api_response' => $response->body()
                ]);
                
                return response()->json([
                    'success' => false, 
                    'message' => 'Lưu hóa đơn thất bại: Lỗi API bên trong. (' . $response->status() . ')'
                ], $response->status() ?: 500);
            }

            // API trả về thành công (thường là HTTP 201 Created)
            $apiResult = $response->json();
            
            // 4. Trả về kết quả cho Frontend (AJAX)
            return response()->json($apiResult, 200);

        } catch (\Exception $e) {
            Log::error('Lỗi Exception khi gọi API lưu hóa đơn: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Lỗi kết nối hoặc ngoại lệ server.'
            ], 500);
        }
    }


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
            // Sửa lại: Dùng Http::get thay vì Http::post
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