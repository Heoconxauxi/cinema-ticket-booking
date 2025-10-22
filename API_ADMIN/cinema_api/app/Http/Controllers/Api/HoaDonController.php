<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use App\Models\ChiTietHoaDon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class HoaDonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hoaDons = HoaDon::with('nguoidung')->orderBy('NgayLapHD', 'desc')->get();
        return response()->json(['success' => true, 'data' => $hoaDons]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'MaND' => 'required|integer|exists:nguoidung,MaND',
            'TongTien' => 'required|integer',
            'NguoiTao' => 'required|integer',
            'details' => 'required|array|min:1',
            'details.*.MaSuatChieu' => 'required|integer|exists:suatchieu,MaSuatChieu',
            'details.*.MaGhe' => 'required|integer|exists:ghe,MaGhe',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        DB::beginTransaction();
        try {
            $hoaDon = HoaDon::create([
                'MaND' => $request->MaND,
                'NgayLapHD' => now(),
                'TongTien' => $request->TongTien,
                'NguoiTao' => $request->NguoiTao,
                'NgayTao' => now(),
                'TrangThai' => 1,
            ]);

            $chiTietData = [];
            foreach ($request->details as $detail) {
                $chiTietData[] = [
                    'MaHD' => $hoaDon->MaHD,
                    'MaSuatChieu' => $detail['MaSuatChieu'],
                    'MaGhe' => $detail['MaGhe'],
                    'NguoiTao' => $request->NguoiTao,
                    'NgayTao' => now(),
                    'TrangThai' => '1',
                ];
            }
            ChiTietHoaDon::insert($chiTietData);

            DB::commit();
            
            $hoaDon->load('nguoidung', 'chitiethoadons');
            
            return response()->json([
                'success' => true,
                'message' => 'Tạo hóa đơn thành công!',
                'data' => $hoaDon
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack(); 
            return response()->json([
                'success' => false,
                'message' => 'Tạo hóa đơn thất bại. Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $hoaDon = HoaDon::with([
            'nguoidung',
            'chitiethoadons.suatchieu.phim',
            'chitiethoadons.ghe.phong'
        ])->where('MaHD', $id)->firstOrFail();

        return response()->json(['success' => true, 'data' => $hoaDon]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HoaDon $hoaDon)
    {
        $validator = Validator::make($request->all(), [
            'TrangThai' => 'required|boolean',
            'NguoiCapNhat' => 'required|integer',
            'TongTien' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $hoaDon->update([
            'TrangThai' => $request->TrangThai,
            'NguoiCapNhat' => $request->NguoiCapNhat,
            'NgayCapNhat' => now(),
            'TongTien' => $request->TongTien ?? $hoaDon->TongTien,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật hóa đơn thành công!',
            'data' => $hoaDon
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HoaDon $hoaDon)
    {
        $hoaDon->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Xóa hóa đơn (và chi tiết) thành công!'
        ]);
    }
}
