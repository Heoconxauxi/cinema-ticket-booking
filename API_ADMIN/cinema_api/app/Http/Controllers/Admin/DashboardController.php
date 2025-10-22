<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Lấy tên người dùng để chào
        $tenNguoiDung = Auth::user()->TenND; //
        return view('admin.dashboard.dashboard', ['tenNguoiDung' => $tenNguoiDung]);
    }
}
