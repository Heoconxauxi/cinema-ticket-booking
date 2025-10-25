<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DatVeController extends Controller
{
    // URL của bạn là /phim/{idPhim}/dat-ve/{idSuat}/{idPhong}
    // Mặc dù $idPhim và $idPhong không cần thiết nếu đã có $idSuat, 
    // chúng ta cứ giữ nguyên để không làm hỏng route của bạn.
    public function index($idPhim, $idSuat, $idPhong)
    {
        $api = "http://127.0.0.1:8000/api";

        // 1. CHỈ CẦN GỌI API NÀY
        $suatRes = Http::get("$api/suatchieu/$idSuat")->json();

        // 2. Kiểm tra API
        if (empty($suatRes['success']) || empty($suatRes['data'])) {
            return back()->with('error', 'Không tìm thấy suất chiếu hoặc dữ liệu không hợp lệ.');
        }

        $suat = $suatRes['data'];
        
        // 3. Lấy dữ liệu từ 1 lần gọi duy nhất
        $phim = $suat['phim'] ?? null;
        $ghes = $suat['phong']['ghes'] ?? []; // Lấy TẤT CẢ ghế trong phòng

        // 4. Lấy danh sách MaGhe ĐÃ BÁN (đã có trong hóa đơn)
        $chiTietHoaDons = $suat['chi_tiet_hoa_dons'] ?? [];
        
        // Tạo một mảng chỉ chứa các MaGhe đã bị đặt cho suất chiếu NÀY
        $gheDaBanIds = array_map(function($ct) {
            return $ct['MaGhe'];
        }, $chiTietHoaDons);

        // Kiểm tra dữ liệu phụ
        if (empty($phim) || empty($ghes)) {
             return back()->with('error', 'Dữ liệu suất chiếu bị lỗi (không tìm thấy phim hoặc phòng).');
        }

        // 5. Trả về view với đầy đủ dữ liệu
        return view('dat-ve', compact('phim', 'suat', 'ghes', 'gheDaBanIds'));
    }
}