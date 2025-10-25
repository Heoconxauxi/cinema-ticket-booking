<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class IndexController extends Controller
{
    public function index()
    {
        try {
            // Gọi API suất chiếu
            $suatResponse = Http::get('http://127.0.0.1:8000/api/suatchieu');
            $suatchieus = $suatResponse->json()['data'] ?? [];

            // Lấy giờ hiện tại
            $now = Carbon::now();
            $phimDangChieu = [];
            $phimSapChieu = [];

            foreach ($suatchieus as $suat) {
                $gio = Carbon::parse($suat['GioChieu']);
                $phim = $suat['phim'] ?? null;

                if (!$phim) continue;

                // Phân loại phim
                if ($gio->isPast() || $gio->isToday()) {
                    $phimDangChieu[$phim['MaPhim']] = $phim;
                } else {
                    $phimSapChieu[$phim['MaPhim']] = $phim;
                }
            }

            // Gọi API slider
            $sliderResponse = Http::get('http://127.0.0.1:8000/api/slider');
            $sliders = $sliderResponse->json() ?? [];

            return view('index', compact('phimDangChieu', 'phimSapChieu', 'sliders'));
        } catch (\Exception $e) {
            return view('index', [
                'phimDangChieu' => [],
                'phimSapChieu' => [],
                'sliders' => [],
                'error' => 'Không thể kết nối đến API: ' . $e->getMessage()
            ]);
        }
    }
}
