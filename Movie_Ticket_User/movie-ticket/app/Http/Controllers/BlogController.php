<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // Trang danh sách blog
    public function index()
    {
        $apiUrl = 'http://127.0.0.1:8000/api/baiviet';
        $response = Http::get($apiUrl);

        if ($response->successful()) {
            $baiVietList = $response->json()['data'] ?? [];
        } else {
            $baiVietList = [];
        }

        return view('blog.index', compact('baiVietList'));
    }

    // Trang chi tiết blog (dựa vào LienKet)
    public function show($slug)
    {
        $apiUrl = 'http://127.0.0.1:8000/api/baiviet';
        $response = Http::get($apiUrl);

        if (!$response->successful()) {
            abort(404);
        }

        $data = $response->json()['data'] ?? [];
        $baiViet = collect($data)->firstWhere('LienKet', $slug);

        if (!$baiViet) {
            abort(404);
        }

        return view('blog.show', compact('baiViet'));
    }
}
