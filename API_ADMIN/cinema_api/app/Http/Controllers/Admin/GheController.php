<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ghe;
use App\Models\Phong;
use App\Models\ThamSo; // Dùng để lấy giá ghế
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class GheController extends Controller
{
    /**
     * Hiển thị danh sách ghế (CÓ TÌM KIẾM)
     */
    public function index(Request $request)
    {
        $per_page = $request->input('per_page', 10);
        $search = $request->input('searchString', ''); // Lấy chuỗi tìm kiếm

        // Eager load quan hệ 'phong' để tránh N+1 query
        $query = Ghe::with('phong');

        if ($search) {
            $query->where(function ($q) use ($search) {
                // Tìm theo Tên Ghế (ví dụ: A1, B5)
                $q->where('TenGhe', 'like', '%' . $search . '%')
                  // Hoặc tìm theo Tên Phòng (ví dụ: Rạp 1)
                  ->orWhereHas('phong', function ($phongQuery) use ($search) {
                      $phongQuery->where('TenPhong', 'like', '%' . $search . '%');
                  });
            });
        }

        $list_ghe = $query->orderBy('MaPhong', 'asc')
                          ->orderBy('TenGhe', 'asc')
                          ->paginate($per_page);

        return view('admin.ghe.index', compact('list_ghe', 'per_page', 'search'));
    }

    /**
     * Hiển thị form tạo ghế mới.
     */
    public function create()
    {
        // Lấy danh sách phòng và các loại giá (tham số)
        $phongs = Phong::where('TrangThai', 1)->orderBy('TenPhong')->get();
        
        // Lấy giá từ bảng thamso
        $giaDon = ThamSo::where('TenThamSo', 'Đơn')->value('GiaTri');
        $giaDoi = ThamSo::where('TenThamSo', 'Đôi')->value('GiaTri');
        $giaVip = ThamSo::where('TenThamSo', 'VIP')->value('GiaTri');

        return view('admin.ghe.create', compact('phongs', 'giaDon', 'giaDoi', 'giaVip'));
    }

    /**
     * Lưu ghế mới.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'MaPhong' => 'required|exists:phong,MaPhong',
            'TenGhe' => [
                'required',
                'string',
                'max:3', // Tên ghế thường là A1, B2...
                // Rule: TenGhe là duy nhất TRONG MaPhong đó
                Rule::unique('ghe')->where(fn ($query) => $query->where('MaPhong', $request->MaPhong))
            ],
            'LoaiGhe' => 'required|string',
            'GiaGhe' => 'required|integer|min:0',
            'TrangThai' => 'nullable'
        ]);

        $data['NguoiTao'] = Auth::id() ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;

        Ghe::create($data);

        return redirect()->route('admin.ghe.index')->with('success', 'Thêm ghế mới thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa ghế.
     */
    public function edit(Ghe $ghe)
    {
        $phongs = Phong::where('TrangThai', 1)->orderBy('TenPhong')->get();
        $ghe->load('phong'); // Tải thông tin phòng

        // Lấy giá từ bảng thamso
        $giaDon = ThamSo::where('TenThamSo', 'Đơn')->value('GiaTri');
        $giaDoi = ThamSo::where('TenThamSo', 'Đôi')->value('GiaTri');
        $giaVip = ThamSo::where('TenThamSo', 'VIP')->value('GiaTri');

        return view('admin.ghe.edit', compact('ghe', 'phongs', 'giaDon', 'giaDoi', 'giaVip'));
    }

    /**
     * Cập nhật ghế.
     */
    public function update(Request $request, Ghe $ghe)
    {
        $data = $request->validate([
            'MaPhong' => 'required|exists:phong,MaPhong',
            'TenGhe' => [
                'required',
                'string',
                'max:3',
                // Rule: TenGhe là duy nhất TRONG MaPhong đó, BỎ QUA chính nó
                Rule::unique('ghe')
                    ->where(fn ($query) => $query->where('MaPhong', $request->MaPhong))
                    ->ignore($ghe->MaGhe, 'MaGhe')
            ],
            'LoaiGhe' => 'required|string',
            'GiaGhe' => 'required|integer|min:0',
            'TrangThai' => 'nullable'
        ]);

        $data['NguoiCapNhat'] = Auth::id() ?? 0;
        $data['TrangThai'] = $request->has('TrangThai') ? 1 : 0;

        $ghe->update($data);

        return redirect()->route('admin.ghe.index')->with('success', 'Cập nhật ghế thành công!');
    }

    /**
     * Xóa ghế.
     */
    public function destroy(Ghe $ghe)
    {
        // CẢNH BÁO: Schema của bạn có ON DELETE CASCADE từ chitiethoadon -> ghe
        // Lệnh này sẽ XÓA VĨNH VIỄN VÉ (ChiTietHoaDon) đã bán cho ghế này
        // ở mọi suất chiếu.
        
        // (Cách an toàn hơn là check:)
        // if ($ghe->chiTietHoaDons()->count() > 0) {
        //     return back()->with('error', 'Không thể xóa ghế đã có vé bán.');
        // }

        $ghe->delete();

        return redirect()->route('admin.ghe.index')->with('success', 'Xóa ghế thành công!');
    }
}