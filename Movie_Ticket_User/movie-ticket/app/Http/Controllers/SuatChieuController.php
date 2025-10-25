<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuatChieuController extends Controller
{
    // Trang giao diện Blade (client)
    public function index()
    {
        return view('suat-chieu'); // gọi view resources/views/suat-chieu.blade.php
    }
}
