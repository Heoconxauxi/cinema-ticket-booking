<?php
use Illuminate\Support\Facades\Route;

// ====================== AUTH =======================
use App\Http\Controllers\AuthController;
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ====================== INDEX =======================
use App\Http\Controllers\IndexController;
Route::get('/', [IndexController::class, 'index'])->name('index');

// ====================== PHIM =======================
use App\Http\Controllers\PhimController;
Route::get('/phim/{id}', [PhimController::class, 'show'])->name('phim.show');

// ====================== LIÊN HỆ =======================
use App\Http\Controllers\PageController;
Route::get('/lien-he', [PageController::class, 'contact'])->name('contact');
Route::post('/lien-he', [PageController::class, 'sendContact'])->name('contact.send');

// ====================== SUẤT CHIẾU =======================
use App\Http\Controllers\SuatChieuController;
Route::get('/suat-chieu', [SuatChieuController::class, 'index'])->name('suat-chieu');

// ====================== ĐẶT VÉ =======================
use App\Http\Controllers\DatVeController;
Route::get('/phim/{idPhim}/dat-ve/{idSuat}/{idPhong}', [DatVeController::class, 'index'])->name('datve');
Route::view('/thanhtoan', 'thanhtoan')->name('thanhtoan');

// ====================== QUẢN LÝ VÉ =======================
use App\Http\Controllers\VeController; 
Route::get('/tickets', [VeController::class, 'index'])->name('tickets.index'); 
Route::get('/tickets/{id}', [VeController::class, 'show'])->name('tickets.show'); 

// ====================== HỒ SƠ NGƯỜI DÙNG =======================
use App\Http\Controllers\ProfileController;
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

// ====================== BLOG =======================
use App\Http\Controllers\BlogController;
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// ====================== API: LẤY THÔNG TIN NGƯỜI DÙNG HIỆN TẠI =======================
use App\Http\Controllers\Api\NguoiDungController;
Route::get('/api/current-user', [NguoiDungController::class, 'getCurrentUser'])->name('api.currentUser');
