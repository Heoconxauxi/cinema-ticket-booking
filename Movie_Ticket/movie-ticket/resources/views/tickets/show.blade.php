@extends('layouts.app')
{{-- Lấy tên phim chung từ chi tiết đầu tiên --}}
@php
    $firstDetail = $hoaDon['chitiethoadons'][0] ?? null;
    $phim = $firstDetail['suatchieu']['phim'] ?? null;
    $suatChieu = $firstDetail['suatchieu'] ?? null;
    $phong = $firstDetail['ghe']['phong'] ?? null; // Lấy phòng từ ghế của chi tiết đầu tiên
    $tenPhim = $phim['TenPhim'] ?? 'Không rõ';
@endphp
@section('title', 'Chi Tiết Vé - ' . $tenPhim)

@section('content')
<div class="container mt-5 text-white">
    {{-- Tiêu đề chung --}}
    <h2 class="text-warning mb-4 text-center">Chi Tiết Vé Đã Đặt</h2>
    <p class="text-center text-muted mb-4">Mã Hóa Đơn: #{{ $hoaDon['MaHD'] }} | Ngày đặt: {{ \Carbon\Carbon::parse($hoaDon['NgayLapHD'])->format('H:i d/m/Y') }}</p>

    {{-- 🔥 Bắt đầu vòng lặp để tạo vé cho mỗi ghế --}}
    <div class="row justify-content-center g-4"> {{-- Thêm row và g-4 để có khoảng cách --}}
        @forelse ($hoaDon['chitiethoadons'] as $chiTiet)
            @php
                // Lấy thông tin ghế cụ thể cho vé này
                $ghe = $chiTiet['ghe'] ?? null;
                $tenGhe = $ghe['TenGhe'] ?? '?';
                $giaGhe = $ghe['GiaGhe'] ?? 0; // Lấy giá ghế (nếu có)
                // Phim, Suất, Phòng lấy từ biến chung ở trên
            @endphp
            <div class="col-md-6 col-lg-4 d-flex justify-content-center"> {{-- Bootstrap column --}}
                <div class="ticket-card bg-light text-dark rounded shadow-lg p-4" style="width: 100%; max-width: 380px; border-left: 8px solid #dc3545;">

                    {{-- Header Vé --}}
                    <div class="text-center mb-3 pb-2 border-bottom border-dark border-2">
                        <h5 class="text-danger fw-bold mb-1">WAPP.C-11 CINEMA</h5>
                        <p class="mb-0 small">--- Vé Xem Phim ---</p>
                        {{-- <p class="mb-0 small text-muted">Mã Hóa Đơn: #{{ $hoaDon['MaHD'] }}</p> --}} {{-- Có thể ẩn mã HĐ ở đây vì đã có ở trên --}}
                    </div>

                    {{-- Thông tin phim --}}
                    <div class="mb-3">
                        <h6 class="fw-bold">{{ $tenPhim }}</h6>
                        @if ($phim)
                            <p class="small mb-0 text-muted">{{ $phim['PhanLoai'] ?? '' }} | {{ $phim['ThoiLuong'] ?? 'N/A' }} phút</p>
                        @endif
                    </div>

                    {{-- Thông tin Suất chiếu --}}
                    <div class="row mb-3 fs-6"> {{-- Giảm cỡ chữ chút --}}
                        <div class="col-7">
                            <p class="mb-1"><strong class="text-primary small">Ngày chiếu:</strong></p>
                            <p class="fw-bold">{{ $suatChieu ? \Carbon\Carbon::parse($suatChieu['GioChieu'])->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                        <div class="col-5 border-start border-secondary">
                            <p class="mb-1"><strong class="text-primary small">Giờ chiếu:</strong></p>
                             <p class="fw-bold">{{ $suatChieu ? \Carbon\Carbon::parse($suatChieu['GioChieu'])->format('H:i') : 'N/A' }}</p>
                        </div>
                    </div>

                     {{-- Thông tin Phòng & Ghế (CHỈ HIỆN 1 GHẾ) --}}
                    <div class="row mb-3 bg-secondary bg-opacity-10 p-2 rounded align-items-center">
                         <div class="col-5">
                             <p class="mb-1 small">Phòng:</p>
                            <p class="fw-bold fs-5 mb-0">{{ $phong['TenPhong'] ?? 'N/A' }}</p>
                         </div>
                         <div class="col-7 border-start border-secondary">
                             <p class="mb-1 small">Ghế:</p>
                             <p class="fw-bold fs-1 mb-0 text-danger">{{ $tenGhe }}</p> {{-- Chỉ hiện 1 tên ghế, cỡ chữ lớn --}}
                         </div>
                    </div>

                    {{-- Giá vé (cho ghế này) --}}
                    <div class="mb-3 text-end">
                         <p class="mb-0 fw-bold fs-5 text-success">Giá vé: {{ number_format($giaGhe, 0, ',', '.') }} đ</p>
                         <p class="small text-muted">(Đã bao gồm VAT)</p>
                    </div>


                     {{-- Lưu ý --}}
                    <div class="text-center mt-3 pt-2 border-top border-dashed">
                        <p class="small mb-0 fst-italic">Quý khách vui lòng giữ vé để kiểm soát.</p>
                        <p class="small fw-bold text-danger">VÉ KHÔNG ĐƯỢC HOÀN TRẢ.</p>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning text-center">Không tìm thấy chi tiết vé nào cho hóa đơn này.</div>
            </div>
        @endforelse
    </div> {{-- Kết thúc row --}}
    {{-- 🔥 Kết thúc vòng lặp --}}

    {{-- Nút quay lại đặt ở cuối --}}
    <div class="text-center mt-5">
         <a href="{{ route('tickets.index') }}" class="btn btn-outline-light">← Quay lại danh sách vé</a>
     </div>

</div>

<style>
    .ticket-card {
        font-family: 'Courier New', Courier, monospace;
        transition: transform 0.2s ease-in-out; /* Thêm hiệu ứng hover nhẹ */
    }
    .ticket-card:hover {
        transform: translateY(-5px);
    }
    .border-dashed {
        border-top: 2px dashed #6c757d;
    }
</style>
@endsection