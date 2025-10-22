<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TaiKhoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $taiKhoans = TaiKhoan::with('nguoidung')->get();
        return response()->json(['success' => true, 'data' => $taiKhoans]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenDangNhap' => 'required|string|max:50|unique:taikhoan,TenDangNhap',
            'MatKhau' => 'required|string|min:6|max:100',
            'TenND' => 'required|string|max:200',
            'Quyen' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $taiKhoan = TaiKhoan::create([
                'TenDangNhap' => $request->TenDangNhap,
                'MatKhau' => Hash::make($request->MatKhau),
                'TenND' => $request->TenND,
                'Quyen' => $request->Quyen,
            ]);

            NguoiDung::create([
                'MaND' => $taiKhoan->MaND,
                'TenND' => $request->TenND,
                'NguoiTao' => $request->NguoiTao ?? 1,
                'NgayTao' => now(),
                'TrangThai' => 1,
                'Email' => $request->Email ?? null,
            ]);

            DB::commit(); 
            
            $taiKhoan->load('nguoidung');
            
            return response()->json([
                'success' => true,
                'message' => 'Tạo tài khoản thành công!',
                'data' => $taiKhoan
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json([
                'success' => false,
                'message' => 'Tạo tài khoản thất bại. Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TaiKhoan $taiKhoan)
    {
        $taiKhoan->load('nguoidung');
        return response()->json(['success' => true, 'data' => $taiKhoan]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaiKhoan $taiKhoan)
    {
        $validator = Validator::make($request->all(), [
            'TenDangNhap' => 'required|string|max:50|unique:taikhoan,TenDangNhap,' . $taiKhoan->MaND . ',MaND',
            'MatKhau' => 'nullable|string|min:6|max:100',
            'TenND' => 'required|string|max:200',
            'Quyen' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $data = [
                'TenDangNhap' => $request->TenDangNhap,
                'TenND' => $request->TenND,
                'Quyen' => $request->Quyen,
            ];
            
            if ($request->filled('MatKhau')) {
                $data['MatKhau'] = Hash::make($request->MatKhau);
            }
            
            $taiKhoan->update($data);

            if ($taiKhoan->nguoidung) {
                $taiKhoan->nguoidung->update([
                    'TenND' => $request->TenND,
                ]);
            }
            
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật tài khoản thành công!',
                'data' => $taiKhoan
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Cập nhật thất bại. Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaiKhoan $taiKhoan)
    {
        try {
            $taiKhoan->delete(); 
            
            return response()->json([
                'success' => true,
                'message' => 'Xóa tài khoản (và profile) thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xóa thất bại. Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
