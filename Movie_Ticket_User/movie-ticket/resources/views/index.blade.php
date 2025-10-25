@extends('layouts.app')
@section('title', 'Trang chính')

@section('content')
<div class="container mt-5 text-center">

    {{-- ✅ Kiểm tra đăng nhập --}}
    @if(session('NDloggedIn'))
    <h2>🎬 Xin chào, {{ session('TenND') }}!</h2>
    <p>Chào mừng bạn đến với hệ thống đặt vé phim của <strong>WAPP.C-11</strong>.</p>
    <!-- <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-danger mt-3">🚪 Đăng xuất</button>
    </form> -->
    @else
    <h2>🎟️ Xin chào khách!</h2>
    <p>Bạn chưa đăng nhập. Hãy đăng nhập để đặt vé ngay nhé!</p>
    <a href="{{ route('login') }}" class="btn btn-primary mt-3">🔑 Đăng nhập</a>
    <a href="{{ route('register') }}" class="btn btn-outline-light mt-3 ms-2">✍️ Đăng ký</a>
    @endif

    {{-- 🎞️ SLIDER --}}
    <div id="carouselExample" class="carousel slide mt-5" data-bs-ride="carousel" data-bs-interval="2000">
        <div class="carousel-inner">
            @foreach($sliders as $index => $slider)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <img src="{{ $slider['Anh'] }}" class="d-block w-100 rounded" alt="{{ $slider['TenSlider'] }}">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
                    <h5>{{ $slider['TenSlider'] }}</h5>
                    <p>{{ $slider['MoTa'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    {{-- 🎥 PHIM ĐANG CHIẾU --}}
    <div class="mt-5">
        <h3 class="text-warning mb-3">🎥 Phim Đang Chiếu</h3>
        <div class="row justify-content-center">
            @forelse($phimDangChieu as $phim)
            <div class="col-md-3 mb-4">
                <a href="{{ route('phim.show', ['id' => $phim['MaPhim']]) }}" class="text-decoration-none text-white">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ $phim['Anh'] }}" class="card-img-top" alt="{{ $phim['TenPhim'] }}">
                        <div class="card-body">
                            <h6 class="card-title text-white">{{ $phim['TenPhim'] }}</h6>
                            <p class="small text-white-50">{{ $phim['PhanLoai'] }}</p>
                            <a href="{{ route('phim.show', ['id' => $phim['MaPhim']]) }}" class="btn btn-sm btn-outline-primary">🎬 Xem chi tiết</a>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <p>Chưa có phim đang chiếu</p>
            @endforelse
        </div>
    </div>

    {{-- 🎬 PHIM SẮP CHIẾU --}}
    <div class="mt-5">
        <h3 class="text-info mb-3">🎬 Phim Sắp Chiếu</h3>
        <div class="row justify-content-center">
            @forelse($phimSapChieu as $phim)
            <div class="col-md-3 mb-4">
                <a href="{{ route('phim.show', ['id' => $phim['MaPhim']]) }}" class="text-decoration-none text-white">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ $phim['Anh'] }}" class="card-img-top" alt="{{ $phim['TenPhim'] }}">
                        <div class="card-body">
                            <h6 class="card-title text-white">{{ $phim['TenPhim'] }}</h6>
                            <p class="small text-white-50">{{ $phim['PhanLoai'] }}</p>
                            <a href="{{ route('phim.show', ['id' => $phim['MaPhim']]) }}" class="btn btn-sm btn-outline-primary">🎬 Xem chi tiết</a>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <p>Chưa có phim sắp chiếu</p>
            @endforelse
        </div>
    </div>

</div>
@endsection