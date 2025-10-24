<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SuatChieu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuatChieuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suatChieus = SuatChieu::with('phim', 'phong')->get();
        
        return response()->json([
            'success' => true,
            'data' => $suatChieus
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'MaPhim' => 'required|integer|exists:phim,MaPhim',
            'MaPhong' => 'required|integer|exists:phong,MaPhong',
            'GioChieu' => 'required|date',
            'NguoiTao' => 'required|integer',
            'TrangThai' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $suatChieu = SuatChieu::create([
            'MaPhim' => $request->MaPhim,
            'MaPhong' => $request->MaPhong,
            'GioChieu' => $request->GioChieu,
            'NguoiTao' => $request->NguoiTao,
            'NgayTao' => now(),
            'TrangThai' => $request->TrangThai,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tạo suất chiếu thành công!',
            'data' => $suatChieu
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $suatChieu = SuatChieu::with([
            'phim',
            'phong.ghes',
            'chiTietHoaDons'
        ])->where('MaSuatChieu', $id)->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => $suatChieu
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuatChieu $suatChieu)
    {
        $validator = Validator::make($request->all(), [
            'MaPhim' => 'required|integer|exists:phim,MaPhim',
            'MaPhong' => 'required|integer|exists:phong,MaPhong',
            'GioChieu' => 'required|date',
            'NguoiCapNhat' => 'required|integer',
            'TrangThai' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $suatChieu->update([
            'MaPhim' => $request->MaPhim,
            'MaPhong' => $request->MaPhong,
            'GioChieu' => $request->GioChieu,
            'NguoiCapNhat' => $request->NguoiCapNhat,
            'NgayCapNhat' => now(),
            'TrangThai' => $request->TrangThai,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật suất chiếu thành công!',
            'data' => $suatChieu
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuatChieu $suatChieu)
    {
        try {
            $suatChieu->delete();
            return response()->json([
                'success' => true,
                'message' => 'Xóa suất chiếu thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa suất chiếu. Có thể đã có vé được bán cho suất này.'
            ], 500);
        }
    }
}
