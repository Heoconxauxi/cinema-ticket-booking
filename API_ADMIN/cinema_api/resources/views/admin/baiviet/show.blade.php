@extends('admin.layouts.app')

{{-- Lấy tên bài viết làm tiêu đề trang --}}
@section('title', 'Chi tiết: ' . $baiviet->TenBV)

@section('content')
<div class="row">
    <div class="col-xl-10 col-lg-12 mx-auto"> {{-- Cho rộng hơn một chút --}}
        <div class="text-end mb-4">
            <a class="btn btn-info" href="{{ route('admin.baiviet.edit', $baiviet->Id) }}">
                <i class="bi bi-pencil me-2"></i>Sửa
            </a>
            <a class="btn btn-secondary" href="{{ route('admin.baiviet.index') }}">
                Quay lại Danh sách
            </a>
        </div>

        <div class="card">
            <div class="card-header pb-0">
                {{-- Hiển thị tiêu đề lớn --}}
                <h3 class="mb-1">{{ $baiviet->TenBV }}</h3>
                <p class="text-sm mb-2">
                    <span class="fw-bold">Chủ đề:</span>
                    {{-- Hiển thị tên chủ đề từ quan hệ --}}
                    {{ $baiviet->chuDe->TenChuDe ?? 'Không xác định' }}
                    | <span class="fw-bold">Ngày tạo:</span>
                    {{ $baiviet->NgayTao ? $baiviet->NgayTao->format('d/m/Y H:i') : 'N/A' }}
                </p>
            </div>
            <div class="card-body pt-0">
                {{-- Hiển thị ảnh đại diện nếu có --}}
                @if ($baiviet->Anh)
                <img src="{{ $baiviet->Anh }}" alt="{{ $baiviet->TenBV }}" class="img-fluid mb-3" style="max-height: 400px; width: auto; display: block; margin-left: auto; margin-right: auto; border-radius: 8px;" onerror="this.style.display='none';"> {{-- Chỉ ẩn đi nếu lỗi --}}
                @endif

                {{-- Hiển thị mô tả ngắn --}}
                <div class="mb-3 p-3 bg-light border rounded">
                    <p class="fw-bold mb-1">Mô tả ngắn (SEO):</p>
                    <p class="mb-0 text-muted">{{ $baiviet->MoTa }}</p>
                </div>

                {{-- Hiển thị nội dung chi tiết --}}
                <hr>
                <div class="article-content mt-3">
                    {{-- Dùng {!! !!} nếu nội dung có HTML, nhưng CẨN THẬN XSS --}}
                    {{-- Dùng nl2br() để giữ xuống dòng nếu nội dung là plain text --}}
                    <p style="white-space: pre-wrap;">{{ $baiviet->ChiTiet }}</p>
                </div>
                <hr>

                {{-- Hiển thị thông tin khác --}}
                <div class="row text-sm">
                    <div class="col-md-6">
                        <p><span class="fw-bold">Kiểu bài viết:</span> {{ $baiviet->KieuBV ?? '(Trống)' }}</p>
                        <p><span class="fw-bold">Từ khóa (SEO):</span> {{ $baiviet->TuKhoa ?? '(Trống)' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><span class="fw-bold">Người tạo (ID):</span> {{ $baiviet->NguoiTao ?? 'N/A' }}</p>
                        <p><span class="fw-bold">Người cập nhật (ID):</span> {{ $baiviet->NguoiCapNhat ?? 'Chưa có' }}</p>
                        <p><span class="fw-bold">Ngày cập nhật:</span> {{ $baiviet->NgayCapNhat ? $baiviet->NgayCapNhat->format('d/m/Y H:i') : 'Chưa có' }}</p>
                        <p><span class="fw-bold">Trạng thái:</span>
                            @if ($baiviet->TrangThai == 1)
                            <span class="badge bg-success">ON</span>
                            @else
                            <span class="badge bg-secondary">OFF</span>
                            @endif
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
