<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use Illuminate\Http\Request;

class HoaDonController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        $search = $request->input('searchString', '');

        // Eager load 'nguoiDung.taiKhoan' for display and search
        $query = HoaDon::with(['nguoiDung.taiKhoan']); 

        if ($search) {
             $query->where(function ($q) use ($search) {
                // Search by MaHD (Invoice ID)
                $q->where('MaHD', '=', $search) 
                  // Search by User's Name (NguoiDung table)
                  ->orWhereHas('nguoiDung', function ($nq) use ($search) {
                      $nq->where('TenND', 'like', '%' . $search . '%');
                  })
                  // Search by User's Username (TaiKhoan table)
                  ->orWhereHas('nguoiDung.taiKhoan', function ($tq) use ($search) {
                      $tq->where('TenDangNhap', 'like', '%' . $search . '%');
                  });
            });
        }

        $list_hoadon = $query->orderBy('NgayLapHD', 'desc')->paginate($per_page);

        return view('admin.hoadon.index', compact('list_hoadon', 'per_page', 'search'));
    }

    // create() and store() are typically not used for manual invoice creation in admin

    public function show(HoaDon $hoadon)
    {
        // Eager load details with nested relationships for the show page
        $hoadon->load(['nguoiDung.taiKhoan', 'chiTietHoaDons.suatChieu.phim', 'chiTietHoaDons.suatChieu.phong', 'chiTietHoaDons.ghe']);
        
        return view('admin.hoadon.show', compact('hoadon'));
    }

    // edit() and update() are typically not used for manual invoice editing

    public function destroy(HoaDon $hoadon)
    {
        // WARNING: Your SQL has ON DELETE CASCADE from chitiethoadon to hoadon.
        // Deleting HoaDon will PERMANENTLY DELETE all associated tickets (ChiTietHoaDon).
        
        // You might want to add checks here, e.g., only allow deletion of old/cancelled orders.

        $hoadon->delete(); // Cascade delete will handle ChiTietHoaDon

        return redirect()->route('admin.hoadon.index')->with('success', 'Xóa hóa đơn và các chi tiết liên quan thành công!');
    }
}