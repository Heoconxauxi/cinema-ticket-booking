<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Menu::query()->orderBy('Order', 'asc');

        if ($request->has('ViTri')) {
            $query->where('ViTri', $request->ViTri);
        }

        $menus = $query->get();
        return response()->json(['success' => true, 'data' => $menus]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'TenMenu' => 'required|string|max:255',
            'LienKet' => 'nullable|string|max:255',
            'ViTri' => 'nullable|string|max:255',
            'Order' => 'nullable|integer',
            'NguoiTao' => 'required|integer',
            'TrangThai' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $menu = Menu::create([
            'TenMenu' => $request->TenMenu,
            'LienKet' => $request->LienKet,
            'ViTri' => $request->ViTri,
            'Order' => $request->Order,
            'NguoiTao' => $request->NguoiTao,
            'NgayTao' => now(),
            'TrangThai' => $request->TrangThai,
            'KieuMenu' => $request->KieuMenu,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Tạo menu thành công!',
            'data' => $menu
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return response()->json(['success' => true, 'data' => $menu]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validator = Validator::make($request->all(), [
            'TenMenu' => 'required|string|max:255',
            'LienKet' => 'nullable|string|max:255',
            'ViTri' => 'nullable|string|max:255',
            'Order' => 'nullable|integer',
            'NguoiCapNhat' => 'required|integer',
            'TrangThai' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $menu->update([
            'TenMenu' => $request->TenMenu,
            'LienKet' => $request->LienKet,
            'ViTri' => $request->ViTri,
            'Order' => $request->Order,
            'NguoiCapNhat' => $request->NguoiCapNhat,
            'NgayCapNhat' => now(),
            'TrangThai' => $request->TrangThai,
            'KieuMenu' => $request->KieuMenu,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật menu thành công!',
            'data' => $menu
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa menu thành công!'
        ]);
    }
}
