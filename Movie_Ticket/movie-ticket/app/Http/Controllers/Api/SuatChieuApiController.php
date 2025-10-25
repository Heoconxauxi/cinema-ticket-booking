<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SuatChieu;
use Illuminate\Http\Request;

class SuatChieuApiController extends Controller
{
    // Lấy danh sách suất chiếu (kèm thông tin phim)
    public function index()
    {
        $suatchieus = SuatChieu::with('phim')->get();

        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách suất chiếu thành công',
            'data' => $suatchieus
        ], 200);
    }

    // Lấy chi tiết suất chiếu
    public function show($id)
    {
        $suat = SuatChieu::with('phim')->find($id);

        if (!$suat) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy suất chiếu'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $suat
        ], 200);
    }
}
