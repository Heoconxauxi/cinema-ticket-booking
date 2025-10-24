<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Phong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhongController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $phongs = Phong::all();
        
        return response()->json([
            'success' => true,
            'data' => $phongs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenPhong' => 'required|string|max:255|unique:phong',
            'TrangThai' => 'required|boolean',
            'NguoiTao' => 'required|integer' 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422); 
        }

        $phong = Phong::create([
            'TenPhong' => $request->TenPhong,
            'TrangThai' => $request->TrangThai,
            'NguoiTao' => $request->NguoiTao,
            'NgayTao' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tạo phòng thành công!',
            'data' => $phong
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Phong $phong)
    {
        $phong->load('ghes');

        return response()->json([
            'success' => true,
            'data' => $phong
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Phong $phong)
    {        
        $validator = Validator::make($request->all(), [
            'TenPhong' => 'required|string|max:255|unique:phong,TenPhong,' . $phong->MaPhong . ',MaPhong',
            'TrangThai' => 'required|boolean',
            'NguoiCapNhat' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $phong->update([
            'TenPhong' => $request->TenPhong,
            'TrangThai' => $request->TrangThai,
            'NguoiCapNhat' => $request->NguoiCapNhat,
            'NgayCapNhat' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật phòng thành công!',
            'data' => $phong
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Phong $phong)
    {
        try {
            $phong->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Xóa phòng thành công!'
            ], 200); // 200 OK
        
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa phòng. Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
