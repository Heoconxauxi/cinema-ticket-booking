<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class PhimController extends Controller
{
    public function show($id)
    {
        // 🧠 Gọi API chi tiết phim
        $response = Http::get("http://127.0.0.1:8000/api/phim/{$id}");
        $data = $response->json();

        if (!$response->successful() || empty($data['data'])) {
            abort(404, 'Phim không tồn tại.');
        }

        $phim = $data['data'];

        // 🎬 Gọi API suất chiếu
        $suatChieuResponse = Http::get("http://127.0.0.1:8000/api/suatchieu");
        $suatChieuData = $suatChieuResponse->json();

        // Kiểm tra dữ liệu trả về
        $allShowtimes = $suatChieuData['data'] ?? [];

        // 🔍 Lọc các suất chiếu chỉ của phim hiện tại
        $suatChieuPhim = collect($allShowtimes)
            ->where('MaPhim', (int)$id)
            ->values()
            ->toArray();

        // Trả dữ liệu về view
        return view('phim.show', compact('phim', 'suatChieuPhim'));
    }
}
