<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BaiViet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BaiVietController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $baiViets = BaiViet::with('chude')->get(); 
        return response()->json([
            'success' => true,
            'data' => $baiViets
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ChuDeBV' => 'required|integer|exists:chude,Id',
            'TenBV' => 'required|string|max:255',
            'ChiTiet' => 'nullable|string',
            'MoTa' => 'required|string',
            'TuKhoa' => 'required|string|max:255',
            'NguoiTao' => 'required|integer',
            'TrangThai' => 'required|boolean',
            'Anh' => 'nullable|string|max:255' 
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        
        $lienKet = Str::slug($request->TenBV);
        
        $count = BaiViet::where('LienKet', $lienKet)->count();
        if ($count > 0) {
            $lienKet = $lienKet . '-' . ($count + 1);
        }

        $baiViet = BaiViet::create([
            'ChuDeBV' => $request->ChuDeBV,
            'TenBV' => $request->TenBV,
            'LienKet' => $lienKet,
            'ChiTiet' => $request->ChiTiet,
            'MoTa' => $request->MoTa,
            'TuKhoa' => $request->TuKhoa,
            'NguoiTao' => $request->NguoiTao,
            'NgayTao' => now(),
            'TrangThai' => $request->TrangThai,
            'Anh' => $request->Anh,
            'KieuBV' => $request->KieuBV,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tạo bài viết thành công!',
            'data' => $baiViet
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(BaiViet $baiViet)
    {
        $baiViet->load('chude');
        return response()->json([
            'success' => true,
            'data' => $baiViet
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BaiViet $baiViet)
    {
        $validator = Validator::make($request->all(), [
            'ChuDeBV' => 'required|integer|exists:chude,Id',
            'TenBV' => 'required|string|max:255',
            'ChiTiet' => 'nullable|string',
            'MoTa' => 'required|string',
            'TuKhoa' => 'required|string|max:255',
            'NguoiCapNhat' => 'required|integer',
            'TrangThai' => 'required|boolean',
            'Anh' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
        
        $lienKet = $baiViet->LienKet;
        if ($request->TenBV != $baiViet->TenBV) {
            $lienKet = Str::slug($request->TenBV);
            $count = BaiViet::where('LienKet', $lienKet)->where('Id', '!=', $baiViet->Id)->count();
            if ($count > 0) {
                $lienKet = $lienKet . '-' . ($count + 1);
            }
        }

        $baiViet->update([
            'ChuDeBV' => $request->ChuDeBV,
            'TenBV' => $request->TenBV,
            'LienKet' => $lienKet,
            'ChiTiet' => $request->ChiTiet,
            'MoTa' => $request->MoTa,
            'TuKhoa' => $request->TuKhoa,
            'NguoiCapNhat' => $request->NguoiCapNhat,
            'NgayCapNhat' => now(),
            'TrangThai' => $request->TrangThai,
            'Anh' => $request->Anh,
            'KieuBV' => $request->KieuBV,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật bài viết thành công!',
            'data' => $baiViet
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BaiViet $baiViet)
    {
        $baiViet->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa bài viết thành công!'
        ]);
    }
}
