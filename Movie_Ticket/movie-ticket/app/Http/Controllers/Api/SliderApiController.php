<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderApiController extends Controller
{
    // Lấy danh sách banner slider
    public function index()
    {
        $sliders = Slider::where('TrangThai', 1)
                         ->orderBy('ThuTu', 'asc')
                         ->get();

        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách slider thành công',
            'data' => $sliders
        ], 200);
    }
}
