<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChuDe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChuDeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chuDes = ChuDe::with('phim')->get();
        
        return response()->json([
            'success' => true,
            'data' => $chuDes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenChuDe' => 'required|string|max:255',
            'MoTa' => 'required|string|max:255',
            'TuKhoa' => 'required|string|max:255',
            'MaPhim' => 'nullable|integer|exists:phim,MaPhim', 
            'NguoiTao' => 'required|integer',
            'TrangThai' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $chuDe = ChuDe::create([
            'TenChuDe' => $request->TenChuDe,
            'MoTa' => $request->MoTa,
            'TuKhoa' => $request->TuKhoa,
            'MaPhim' => $request->MaPhim,
            'NguoiTao' => $request->NguoiTao,
            'NgayTao' => now(),
            'TrangThai' => $request->TrangThai,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tạo chủ đề thành công!',
            'data' => $chuDe
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ChuDe $chuDe)
    {
        $chuDe->load('phim');
        
        return response()->json([
            'success' => true,
            'data' => $chuDe
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ChuDe $chuDe)
    {
        $validator = Validator::make($request->all(), [
            'TenChuDe' => 'required|string|max:255',
            'MoTa' => 'required|string|max:255',
            'TuKhoa' => 'required|string|max:255',
            'MaPhim' => 'nullable|integer|exists:phim,MaPhim',
            'NguoiCapNhat' => 'required|integer',
            'TrangThai' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $chuDe->update([
            'TenChuDe' => $request->TenChuDe,
            'MoTa' => $request->MoTa,
            'TuKhoa' => $request->TuKhoa,
            'MaPhim' => $request->MaPhim,
            'NguoiCapNhat' => $request->NguoiCapNhat,
            'NgayCapNhat' => now(),
            'TrangThai' => $request->TrangThai,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật chủ đề thành công!',
            'data' => $chuDe
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChuDe $chuDe)
    {
        try {
            $chuDe->delete();
            return response()->json([
                'success' => true,
                'message' => 'Xóa chủ đề thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa chủ đề. Có thể đã có bài viết thuộc chủ đề này.'
            ], 500);
        }
    }
}
