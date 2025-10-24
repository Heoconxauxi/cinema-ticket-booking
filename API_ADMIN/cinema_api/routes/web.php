<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PhimController;
use App\Http\Controllers\Admin\TheLoaiController;
use App\Http\Controllers\Admin\SuatChieuController;
use App\Http\Controllers\Admin\PhongController;
use App\Http\Controllers\Admin\GheController;
use App\Http\Controllers\Admin\ChuDeController;
use App\Http\Controllers\Admin\BaiVietController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ThamSoController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\Admin\TaiKhoanController;
use App\Http\Controllers\Admin\HoaDonController;

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
        
        // Logout
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        // ... TẤT CẢ CÁC ROUTE ADMIN KHÁC CỦA BẠN SẼ ĐẶT Ở ĐÂY ...
        // (Ví dụ: Quản lý Phim, Quản lý Suất chiếu, v.v.)
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('phim', PhimController::class);
        Route::resource('theloai', TheLoaiController::class);
        Route::resource('suatchieu', SuatChieuController::class);
        Route::resource('phong', PhongController::class);
        Route::resource('ghe', GheController::class);
        Route::resource('chude', ChuDeController::class);
        Route::resource('baiviet', BaiVietController::class);
        Route::resource('slider', SliderController::class);
        Route::resource('menu', MenuController::class);
        Route::resource('thamso', ThamSoController::class);
        Route::resource('nguoidung', NguoiDungController::class);
        Route::resource('taikhoan', TaiKhoanController::class);
        Route::resource('hoadon', HoaDonController::class);
    });
});

Route::get('/', function () {
    // Kiểm tra xem người dùng đã đăng nhập hay chưa
    if (Auth::check()) { 
        // Nếu đã đăng nhập, chuyển hướng đến trang dashboard admin
        return redirect()->route('admin.dashboard'); // Đảm bảo bạn có route tên 'admin.dashboard'
    }
    // Nếu chưa đăng nhập, chuyển hướng đến trang login
    return redirect()->route('admin.login.form'); 
});