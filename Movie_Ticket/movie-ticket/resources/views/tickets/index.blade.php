@extends('layouts.app')
@section('title', 'Vé Đã Đặt')

@section('content')
<div class="container mt-5 text-white">
    <h2 class="text-warning mb-4 text-center">🎫 Vé Đã Đặt Của Bạn</h2>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if (empty($hoaDons))
        <div class="alert alert-info text-center">Bạn chưa có vé nào.</div>
    @else
        <div class="list-group">
            @foreach ($hoaDons as $hd)
                <a href="{{ route('tickets.show', ['id' => $hd['MaHD']]) }}" class="list-group-item list-group-item-action bg-dark text-white mb-2 rounded shadow-sm ticket-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1 text-warning">Mã HĐ: #{{ $hd['MaHD'] }}</h5>
                        <small class="text-muted">{{ \Carbon\Carbon::parse($hd['NgayLapHD'])->format('H:i - d/m/Y') }}</small>
                    </div>
                    <p class="mb-1">Tổng tiền: <strong class="text-info">{{ number_format($hd['TongTien'], 0, ',', '.') }} đ</strong></p>
                    {{-- Có thể thêm tên phim nếu API trả về --}}
                    {{-- <small class="text-muted">Phim: ... </small> --}}
                </a>
            @endforeach
        </div>
    @endif
</div>

<style>
    .ticket-item {
        border-left: 5px solid #ffc107; /* Màu vàng cam */
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .ticket-item:hover {
        transform: translateX(5px);
        box-shadow: 0 0 15px rgba(255, 193, 7, 0.5);
        border-left-color: #ff9800; /* Cam đậm hơn */
    }
</style>
@endsection