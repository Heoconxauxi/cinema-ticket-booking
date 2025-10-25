<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HoaDon;
use App\Models\NguoiDung;
use App\Models\Phim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB Facade
use Carbon\Carbon; // Import Carbon for date handling

class DashboardController extends Controller
{
    public function index()
    {
        // === Các Chỉ Số Chính ===

        // 1. Tổng Doanh Thu (Tất cả hóa đơn đã thanh toán - Giả sử TrangThai=1 là đã thanh toán)
        $totalRevenue = HoaDon::where('TrangThai', 1)->sum('TongTien');

        // 2. Doanh Thu Hôm Nay
        $todayRevenue = HoaDon::where('TrangThai', 1)
                               ->whereDate('NgayLapHD', Carbon::today())
                               ->sum('TongTien');

        // 3. Tổng Số Khách Hàng (Tổng số người dùng đăng ký)
        $totalCustomers = NguoiDung::count(); // Hoặc TaiKhoan::count() nếu MaND không liên tục

        // 4. Tổng Hóa Đơn (Tất cả hóa đơn)
        $totalOrders = HoaDon::count();

        // 5. Tổng Khách Hàng Hôm Nay (Số người dùng có hóa đơn hôm nay)
        $todayCustomersCount = HoaDon::whereDate('NgayLapHD', Carbon::today())
                                      ->distinct('MaND')
                                      ->count('MaND');

        // 6. Tổng Số Hóa Đơn Hôm Nay
        $todayOrdersCount = HoaDon::whereDate('NgayLapHD', Carbon::today())->count();

        // === Dữ liệu Biểu Đồ ===

        // 7. Biểu đồ Doanh thu theo ngày (Ví dụ: 7 ngày gần nhất)
        $revenueLast7Days = HoaDon::select(
                                DB::raw('DATE(NgayLapHD) as date'), // Lấy ngày
                                DB::raw('SUM(TongTien) as total')   // Tính tổng tiền
                            )
                            ->where('TrangThai', 1)
                            ->where('NgayLapHD', '>=', Carbon::now()->subDays(6)->startOfDay()) // Từ 6 ngày trước đến nay
                            ->groupBy('date') // Nhóm theo ngày
                            ->orderBy('date', 'ASC') // Sắp xếp theo ngày
                            ->get()
                            // Chuyển đổi thành mảng labels và data cho Chart.js
                            ->pluck('total', 'date') 
                            ->toArray(); 
        
        // Chuẩn bị labels và data (đảm bảo đủ 7 ngày, kể cả ngày không có doanh thu)
        $chartLabelsDaily = [];
        $chartDataDaily = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabelsDaily[] = Carbon::now()->subDays($i)->format('d/m'); // Format label dd/mm
            $chartDataDaily[] = $revenueLast7Days[$date] ?? 0; // Nếu ngày đó không có data thì = 0
        }


        // 8. Biểu đồ Doanh thu theo tháng (Ví dụ: 12 tháng gần nhất)
         $revenueLast12Months = HoaDon::select(
                                DB::raw('YEAR(NgayLapHD) as year'),
                                DB::raw('MONTH(NgayLapHD) as month'),
                                DB::raw('SUM(TongTien) as total')
                            )
                            ->where('TrangThai', 1)
                            ->where('NgayLapHD', '>=', Carbon::now()->subMonths(11)->startOfMonth())
                            ->groupBy('year', 'month')
                            ->orderBy('year', 'ASC')
                            ->orderBy('month', 'ASC')
                            ->get()
                            // Tạo key dạng 'YYYY-MM' để dễ map
                            ->keyBy(function ($item) {
                                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
                            }) 
                            ->toArray();

        $chartLabelsMonthly = [];
        $chartDataMonthly = [];
         for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $yearMonth = $date->format('Y-m');
            $chartLabelsMonthly[] = $date->format('m/Y'); // Format label mm/YYYY
            $chartDataMonthly[] = $revenueLast12Months[$yearMonth]['total'] ?? 0;
        }


        // === Top Lists ===

        // 9. Top 5 Khách Hàng Chi Tiêu Cao Nhất
        $topCustomers = HoaDon::select(
                                'hoadon.MaND', // Chọn MaND
                                'nguoidung.TenND', // Lấy TenND từ bảng nguoidung
                                DB::raw('SUM(hoadon.TongTien) as total_spent') // Tính tổng tiền
                            )
                            ->join('nguoidung', 'hoadon.MaND', '=', 'nguoidung.MaND') // Join với bảng nguoidung
                            ->where('hoadon.TrangThai', 1) // Chỉ tính hóa đơn đã thanh toán
                            ->groupBy('hoadon.MaND', 'nguoidung.TenND') // Nhóm theo người dùng
                            ->orderBy('total_spent', 'DESC') // Sắp xếp giảm dần
                            ->limit(5) // Lấy top 5
                            ->get();

        // 10. Top 5 Bộ Phim Doanh Thu Cao Nhất (đang chiếu)
        // Tính tổng giá ghế từ chi tiết hóa đơn liên quan đến phim đang chiếu
         $topMovies = Phim::select(
                            'phim.MaPhim',
                            'phim.TenPhim',
                            DB::raw('SUM(ghe.GiaGhe) as movie_revenue') // Tổng giá ghế
                        )
                        ->join('suatchieu', 'phim.MaPhim', '=', 'suatchieu.MaPhim')
                        ->join('chitiethoadon', 'suatchieu.MaSuatChieu', '=', 'chitiethoadon.MaSuatChieu')
                        ->join('ghe', 'chitiethoadon.MaGhe', '=', 'ghe.MaGhe')
                        // Join với hoadon để lọc theo trạng thái hóa đơn (tùy chọn, nếu cần)
                        // ->join('hoadon', 'chitiethoadon.MaHD', '=', 'hoadon.MaHD') 
                        // ->where('hoadon.TrangThai', 1) // Chỉ tính vé từ hóa đơn đã thanh toán
                        ->where('phim.TrangThai', 1) // Chỉ lấy phim đang chiếu (TrangThai = 1)
                        ->groupBy('phim.MaPhim', 'phim.TenPhim')
                        ->orderBy('movie_revenue', 'DESC')
                        ->limit(5)
                        ->get();


        // === Truyền dữ liệu ra View ===
        return view('admin.dashboard.index', compact(
            'totalRevenue',
            'todayRevenue',
            'totalCustomers',
            'totalOrders',
            'todayCustomersCount',
            'todayOrdersCount',
            'chartLabelsDaily', // Labels cho biểu đồ ngày
            'chartDataDaily',   // Data cho biểu đồ ngày
            'chartLabelsMonthly', // Labels cho biểu đồ tháng
            'chartDataMonthly',   // Data cho biểu đồ tháng
            'topCustomers',
            'topMovies'
        ));
    }
}