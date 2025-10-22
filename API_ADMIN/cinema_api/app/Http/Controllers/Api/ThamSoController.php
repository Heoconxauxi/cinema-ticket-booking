<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ThamSo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ThamSoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $thamSos = ThamSo::all();
        return response()->json(['success' => true, 'data' => $thamSos]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenThamSo' => 'required|string|max:255|unique:thamso,TenThamSo',
            'GiaTri' => 'required|string|max:255',
            'DonViTinh' => 'nullable|string|max:255',
            'TrangThai' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $thamSo = ThamSo::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Tạo tham số thành công!',
            'data' => $thamSo
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ThamSo $thamSo)
    {
        return response()->json(['success' => true, 'data' => $thamSo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ThamSo $thamSo)
    {
        $validator = Validator::make($request->all(), [
            'TenThamSo' => 'required|string|max:255|unique:thamso,TenThamSo,' . $thamSo->Id . ',Id',
            'GiaTri' => 'required|string|max:255',
            'DonViTinh' => 'nullable|string|max:255',
            'TrangThai' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $thamSo->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật tham số thành công!',
            'data' => $thamSo
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ThamSo $thamSo)
    {
        $thamSo->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa tham số thành công!'
        ]);
    }

    /**
     * Lấy danh sách các loại ghế.
     * API: GET /api/seat-types
     */
    public function getSeatTypes()
    {
        $seatTypes = ThamSo::whereIn('TenThamSo', ['Đơn', 'Đôi', 'VIP'])
                            ->where('TrangThai', 1)
                            ->get();
        return response()->json(['success' => true, 'data' => $seatTypes]);
    }

    /**
     * Lấy danh sách các hạng khách hàng.
     * API: GET /api/customer-tiers
     */
    public function getCustomerTiers()
    {
        $tiers = ThamSo::whereIn('TenThamSo', ['Silver', 'Gold', 'Platinum'])
                        ->where('TrangThai', 1)
                        ->get();
        return response()->json(['success' => true, 'data' => $tiers]);
    }
}
