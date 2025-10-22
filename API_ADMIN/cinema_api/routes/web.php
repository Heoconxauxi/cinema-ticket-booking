<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PhimController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\TheLoaiController;
use App\Http\Controllers\Admin\SuatChieuController;
use App\Http\Controllers\Admin\PhongController;
use App\Http\Controllers\Admin\GheController;
use App\Http\Controllers\Admin\ChuDeController;
use App\Http\Controllers\Admin\BaiVietController;
use App\Http\Controllers\Admin\MenuController;

// --- ADMIN ROUTES ---
Route::prefix('admin')->name('admin.')->group(function () {
    
    // Các route cho khách (chưa đăng nhập)
    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
        Route::post('/login', [LoginController::class, 'login'])->name('login');
    });

    // Các route yêu cầu đăng nhập VÀ phải là Admin/Nhân viên
    // Chúng ta dùng middleware 'auth' (mặc định của Laravel)
    // và 'auth.admin' (chúng ta vừa tạo)
    Route::middleware(['auth', 'auth.admin'])->group(function () {
        //Home
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Logout
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        // ... TẤT CẢ CÁC ROUTE ADMIN KHÁC CỦA BẠN SẼ ĐẶT Ở ĐÂY ...
        // (Ví dụ: Quản lý Phim, Quản lý Suất chiếu, v.v.)
        Route::resource('phim', PhimController::class);
        Route::resource('theloai', TheLoaiController::class);
        Route::resource('suatchieu', SuatChieuController::class);
        Route::resource('phong', PhongController::class);
        Route::resource('ghe', GheController::class);
        Route::resource('chude', ChuDeController::class);
        Route::resource('baiviet', BaiVietController::class);
        Route::resource('menu', MenuController::class);

    });
});

// Thêm một route gốc để chuyển hướng
Route::get('/', function () {
    // Chúng ta dùng route() helper với tên route 'admin.login.form'
    return 'Đây là trang chủ. Tới <a href="' . route('admin.login.form') . '">Admin Login</a>';
});
