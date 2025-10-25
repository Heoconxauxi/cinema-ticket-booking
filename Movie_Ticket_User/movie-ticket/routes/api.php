<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\TaiKhoanApiController;
use App\Http\Controllers\Api\PhimApiController;
use App\Http\Controllers\Api\SuatChieuApiController;
use App\Http\Controllers\Api\SliderApiController;

// ========================= //
//  AUTHENTICATION (API)    //
// ========================= //
Route::post('/login', [AuthApiController::class, 'login']);
Route::get('/logout', [AuthApiController::class, 'logout']);

// ========================= //
//  TÀI KHOẢN (API)          //
// ========================= //
Route::get('/taikhoan', [TaiKhoanApiController::class, 'index']);

// ========================= //
//  PHIM & SUẤT CHIẾU (API) //
// ========================= //
Route::get('/phim', [PhimApiController::class, 'index']);          // danh sách phim
Route::get('/phim/{id}', [PhimApiController::class, 'show']);      // chi tiết phim
Route::get('/suatchieu', [SuatChieuApiController::class, 'index']); // tất cả suất chiếu
Route::get('/suatchieu/{id}', [SuatChieuApiController::class, 'show']); // chi tiết suất

// ========================= //
//  SLIDER (API)             //
// ========================= //
Route::get('/slider', [SliderApiController::class, 'index']); // banner / carousel
