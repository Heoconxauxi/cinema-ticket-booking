<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ghe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Ghe::query();

        if ($request->has('MaPhong')) {
            $query->where('MaPhong', $request->MaPhong);
        }

        $ghes = $query->with('phong')->get();

        return response()->json([
            'success' => true,
            'data' => $ghes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenGhe' => 'required|string|max:3',
            'MaPhong' => 'required|integer|exists:phong,MaPhong',
            'LoaiGhe' => 'required|string|max:10',
            'GiaGhe' => 'required|integer',
            'NguoiTao' => 'required|integer',
            'TrangThai' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $ghe = Ghe::create([
            'TenGhe' => $request->TenGhe,
            'MaPhong' => $request->MaPhong,
            'LoaiGhe' => $request->LoaiGhe,
            'GiaGhe' => $request->GiaGhe,
            'NguoiTao' => $request->NguoiTao,
            'NgayTao' => now(),
            'TrangThai' => $request->TrangThai,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tạo ghế thành công!',
            'data' => $ghe
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ghe $ghe)
    {
        $ghe->load('phong'); 

        return response()->json([
            'success' => true,
            'data' => $ghe
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ghe $ghe)
    {
        $validator = Validator::make($request->all(), [
            'TenGhe' => 'required|string|max:3',
            'MaPhong' => 'required|integer|exists:phong,MaPhong',
            'LoaiGhe' => 'required|string|max:10',
            'GiaGhe' => 'required|integer',
            'NguoiCapNhat' => 'required|integer',
            'TrangThai' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $ghe->update([
            'TenGhe' => $request->TenGhe,
            'MaPhong' => $request->MaPhong,
            'LoaiGhe' => $request->LoaiGhe,
            'GiaGhe' => $request->GiaGhe,
            'NguoiCapNhat' => $request->NguoiCapNhat,
            'NgayCapNhat' => now(),
            'TrangThai' => $request->TrangThai,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật ghế thành công!',
            'data' => $ghe
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ghe $ghe)
    {
        try {
            $ghe->delete();
            return response()->json([
                'success' => true,
                'message' => 'Xóa ghế thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa ghế này. Có thể ghế đã được đặt trong một hóa đơn.'
            ], 500);
        }
    }
}
