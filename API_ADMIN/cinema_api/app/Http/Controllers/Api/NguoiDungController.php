<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NguoiDungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nguoiDungs = NguoiDung::with('taikhoan')->get();
        return response()->json(['success' => true, 'data' => $nguoiDungs]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Việc tạo người dùng được thực hiện thông qua /api/taikhoan'
        ], 405); // 405 Method Not Allowed
    }

    /**
     * Display the specified resource.
     */
    public function show(NguoiDung $nguoiDung)
    {
        $nguoiDung->load('taikhoan');
        return response()->json(['success' => true, 'data' => $nguoiDung]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NguoiDung $nguoiDung)
    {
        $validator = Validator::make($request->all(), [
            'TenND' => 'required|string|max:255',
            'NgaySinh' => 'nullable|date',
            'GioiTinh' => 'nullable|boolean',
            'SDT' => 'nullable|string|max:10',
            'Email' => 'nullable|email|max:255',
            'Anh' => 'nullable|string|max:255',
            'NguoiCapNhat' => 'required|integer',
            'TrangThai' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $nguoiDung->update([
            'TenND' => $request->TenND,
            'NgaySinh' => $request->NgaySinh,
            'GioiTinh' => $request->GioiTinh,
            'SDT' => $request->SDT,
            'Email' => $request->Email,
            'Anh' => $request->Anh,
            'NguoiCapNhat' => $request->NguoiCapNhat,
            'NgayCapNhat' => now(),
            'TrangThai' => $request->TrangThai,
        ]);
        
        if ($nguoiDung->taikhoan) {
            $nguoiDung->taikhoan->update([
                'TenND' => $request->TenND,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật profile thành công!',
            'data' => $nguoiDung
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NguoiDung $nguoiDung)
    {
        try {
            $nguoiDung->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Xóa người dùng (và tài khoản) thành công!'
            ]);
        } catch (\Exception $e) {
             return response()->json([
                'success' => false,
                'message' => 'Xóa thất bại. Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }
}
