<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Phim;
use Illuminate\Http\Request;

class PhimApiController extends Controller
{
    // Lấy toàn bộ danh sách phim
    public function index()
    {
        $phim = Phim::all();

        return response()->json([
            'status' => true,
            'message' => 'Lấy danh sách phim thành công',
            'data' => $phim
        ], 200);
    }

    // Lấy chi tiết 1 phim
    public function show($id)
    {
        $phim = Phim::find($id);

        if (!$phim) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy phim'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $phim
        ], 200);
    }
}
