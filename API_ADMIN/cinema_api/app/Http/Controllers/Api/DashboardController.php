<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HoaDon;
use App\Models\TaiKhoan;
use App\Models\NguoiDung;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getStats(Request $request)
    {
        $date = $request->query('date') ? Carbon::parse($request->query('date')) : Carbon::today();

        $doanhThuNgay = HoaDon::whereDate('NgayLapHD', $date)->sum('TongTien');

        $tongKhachHang = TaiKhoan::where('Quyen', 0)->count();

        $tongHoaDon = HoaDon::count();

        return response()->json([
            'success' => true,
            'data' => [
                'doanhThuNgay' => (int)$doanhThuNgay,
                'tongKhachHang' => $tongKhachHang,
                'tongHoaDon' => $tongHoaDon,
            ]
        ]);
    }

    public function getDailyRevenueReport(Request $request)
    {
        $month = $request->query('month', Carbon::now()->month);
        $year = $request->query('year', Carbon::now()->year);

        $daysInMonth = Carbon::createFromDate($year, $month)->daysInMonth;

        $dailyReport = array_fill(0, $daysInMonth, 0);

        $report = HoaDon::select(
                DB::raw('DAY(NgayLapHD) as ngay'),
                DB::raw('SUM(TongTien) as tongDoanhThu')
            )
            ->whereYear('NgayLapHD', $year)
            ->whereMonth('NgayLapHD', $month)
            ->groupBy(DB::raw('DAY(NgayLapHD)'))
            ->orderBy('ngay', 'asc')
            ->get();

        foreach ($report as $r) {
            $dailyReport[$r->ngay - 1] = (int)$r->tongDoanhThu;
        }

        return response()->json([
            'success' => true,
            'data' => $dailyReport
        ]);
    }

    public function getMonthlyRevenueReport(Request $request)
    {
        $year = $request->query('year', Carbon::now()->year);

        $report = HoaDon::select(
                DB::raw('MONTH(NgayLapHD) as thang'),
                DB::raw('SUM(TongTien) as tongDoanhThu')
            )
            ->whereYear('NgayLapHD', $year)
            ->groupBy(DB::raw('MONTH(NgayLapHD)'))
            ->orderBy('thang', 'asc')
            ->get();
        

        $monthlyReport = array_fill(0, 12, 0);
        
        foreach ($report as $r) {
            $monthlyReport[$r->thang - 1] = (int)$r->tongDoanhThu;
        }

        return response()->json([
            'success' => true,
            'data' => $monthlyReport
        ]);
    }

    public function getTopCustomers()
    {
        $topCustomers = NguoiDung::join('hoadon', 'nguoidung.MaND', '=', 'hoadon.MaND')
            ->select(
                'nguoidung.TenND', 
                'nguoidung.Email',
                DB::raw('SUM(hoadon.TongTien) as tongChiTieu'),
                DB::raw('COUNT(hoadon.MaHD) as tongHoaDon')
            )
            ->where('nguoidung.MaND', '!=', 3)
            ->groupBy('nguoidung.MaND', 'nguoidung.TenND', 'nguoidung.Email')
            ->orderBy('tongChiTieu', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $topCustomers
        ]);
    }
}