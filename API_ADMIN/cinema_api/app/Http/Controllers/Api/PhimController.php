<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Phim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PhimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $phims = Phim::with('theloais')->where('TrangThai', 1)->get(); 
        return response()->json([
            'success' => true,
            'data' => $phims
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenPhim' => 'required|string|max:255|unique:phim,TenPhim',
            'ThoiLuong' => 'required|integer',
            'DaoDien' => 'required|string|max:255',
            'DienVien' => 'required|string|max:255',
            'QuocGia' => 'required|string|max:255',
            'NamPhatHanh' => 'required|integer',
            'PhanLoai' => 'required|string',
            'MoTa' => 'required|string',
            'NguoiTao' => 'required|integer',
            'TrangThai' => 'required|boolean',
            'Anh' => 'nullable|string|max:255',
            'Banner' => 'nullable|string|max:255',
            
            'TheLoaiIDs' => 'required|array',
            'TheLoaiIDs.*' => 'integer|exists:theloai,MaTheLoai'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $tenRutGon = Str::slug($request->TenPhim);

            $phim = Phim::create([
                'TenPhim' => $request->TenPhim,
                'TenRutGon' => $tenRutGon,
                'ThoiLuong' => $request->ThoiLuong,
                'DaoDien' => $request->DaoDien,
                'DienVien' => $request->DienVien,
                'QuocGia' => $request->QuocGia,
                'NamPhatHanh' => $request->NamPhatHanh,
                'PhanLoai' => $request->PhanLoai,
                'MoTa' => $request->MoTa,
                'NguoiTao' => $request->NguoiTao,
                'NgayTao' => now(),
                'TrangThai' => $request->TrangThai,
                'Anh' => $request->Anh,
                'Banner' => $request->Banner,
            ]);

            $phim->theloais()->attach($request->TheLoaiIDs);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tạo phim thành công!',
                'data' => $phim
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Tạo phim thất bại. Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Phim $phim)
    {
        $phim->load('theloais');
        return response()->json(['success' => true, 'data' => $phim]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Phim $phim)
    {
         $validator = Validator::make($request->all(), [
            'TenPhim' => 'required|string|max:255|unique:phim,TenPhim,' . $phim->MaPhim . ',MaPhim',
            'ThoiLuong' => 'required|integer',
            'DaoDien' => 'required|string|max:255',
            'DienVien' => 'required|string|max:255',
            'QuocGia' => 'required|string|max:255',
            'NamPhatHanh' => 'required|integer',
            'PhanLoai' => 'required|string',
            'MoTa' => 'required|string',
            'NguoiCapNhat' => 'required|integer',
            'TrangThai' => 'required|boolean',
            'Anh' => 'nullable|string|max:255',
            'Banner' => 'nullable|string|max:255',
            'TheLoaiIDs' => 'required|array',
            'TheLoaiIDs.*' => 'integer|exists:theloai,MaTheLoai'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $tenRutGon = $phim->TenRutGon;
            if ($request->TenPhim != $phim->TenPhim) {
                $tenRutGon = Str::slug($request->TenPhim);
            }

            $phim->update([
                'TenPhim' => $request->TenPhim,
                'TenRutGon' => $tenRutGon,
                'ThoiLuong' => $request->ThoiLuong,
                'DaoDien' => $request->DaoDien,
                'DienVien' => $request->DienVien,
                'QuocGia' => $request->QuocGia,
                'NamPhatHanh' => $request->NamPhatHanh,
                'PhanLoai' => $request->PhanLoai,
                'MoTa' => $request->MoTa,
                'NguoiCapNhat' => $request->NguoiCapNhat,
                'NgayCapNhat' => now(),
                'TrangThai' => $request->TrangThai,
                'Anh' => $request->Anh,
                'Banner' => $request->Banner,
            ]);

            $phim->theloais()->sync($request->TheLoaiIDs);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật phim thành công!',
                'data' => $phim
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật phim thất bại. Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Phim $phim)
    {
        try {
            $phim->delete();
            return response()->json([
                'success' => true,
                'message' => 'Xóa phim thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa phim. Có thể phim đang có suất chiếu hoặc chủ đề liên quan.'
            ], 500);
        }
    }
}
