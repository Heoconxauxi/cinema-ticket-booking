<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; 
use App\Http\Controllers\Api\DashboardController; 
use App\Http\Controllers\Api\PhongController; 
use App\Http\Controllers\Api\GheController; 
use App\Http\Controllers\Api\TheLoaiController; 
use App\Http\Controllers\Api\PhimController; 
use App\Http\Controllers\Api\SuatChieuController; 
use App\Http\Controllers\Api\ChuDeController; 
use App\Http\Controllers\Api\BaiVietController; 
use App\Http\Controllers\Api\SliderController; 
use App\Http\Controllers\Api\MenuController; 
use App\Http\Controllers\Api\ThamSoController; 
use App\Http\Controllers\Api\NguoiDungController; 
use App\Http\Controllers\Api\TaiKhoanController; 
use App\Http\Controllers\Api\HoaDonController;

// Đăng nhập
Route::post('/admin/login', [AuthController::class, 'loginAdmin']);
Route::post('/login', [AuthController::class, 'loginUser']);
Route::post('register', [AuthController::class, 'register']);

// Các route yêu cầu token
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admin/logout', [AuthController::class, 'logout']);
    Route::get('/admin/profile', fn(Request $r) => $r->user()->load('nguoidung'));
});

// Dashboard
Route::prefix('dashboard')->group(function () {
    Route::get('stats', [DashboardController::class, 'getStats']);
    Route::get('daily-revenue-report', [DashboardController::class, 'getDailyRevenueReport']);
    Route::get('monthly-revenue-report', [DashboardController::class, 'getMonthlyRevenueReport']);
    Route::get('top-customers', [DashboardController::class, 'getTopCustomers']);
});

// Các API khác
Route::apiResource('phong', PhongController::class);
Route::apiResource('ghe', GheController::class);
Route::apiResource('theloai', TheLoaiController::class);
Route::apiResource('phim', PhimController::class);
Route::apiResource('suatchieu', SuatChieuController::class);
Route::apiResource('chude', ChuDeController::class);
Route::apiResource('baiviet', BaiVietController::class);
Route::apiResource('slider', SliderController::class);
Route::apiResource('menu', MenuController::class);
Route::apiResource('thamso', ThamSoController::class);
Route::get('seat-types', [ThamSoController::class, 'getSeatTypes']);
Route::get('customer-tiers', [ThamSoController::class, 'getCustomerTiers']);
Route::apiResource('hoadon', HoaDonController::class);
Route::apiResource('nguoidung', NguoiDungController::class);
Route::apiResource('taikhoan', TaiKhoanController::class);