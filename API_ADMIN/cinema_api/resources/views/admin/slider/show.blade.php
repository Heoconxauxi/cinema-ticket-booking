@extends('admin.layouts.app')

@section('title', 'Chi tiết Slider: ' . $slider->TenSlider)

@section('content')
<div class="row">
    <div class="col-xl-10 col-lg-12 mx-auto">
        <div class="text-end mb-4">
            <a class="btn btn-info" href="{{ route('admin.slider.edit', $slider->Id) }}">
                <i class="bi bi-pencil me-2"></i>Sửa
            </a>
            <a class="btn btn-secondary" href="{{ route('admin.slider.index') }}">
                Quay lại Danh sách
            </a>
        </div>

        <div class="card">
            <div class="card-header pb-0">
                <h4 class="mb-1">{{ $slider->TenSlider }}</h4>
                <p class="text-sm mb-2">
                    <span class="fw-bold">Vị trí:</span> {{ $slider->ViTri }} |
                    <span class="fw-bold">Thứ tự:</span> {{ $slider->SapXep }} |
                    <span class="fw-bold">Ngày tạo:</span> {{ $slider->NgayTao ? $slider->NgayTao->format('d/m/Y H:i') : 'N/A' }}
                </p>
            </div>
            <div class="card-body pt-0">
                @if ($slider->Anh)
                <img src="{{ $slider->Anh }}" alt="{{ $slider->TenSlider }}" class="img-fluid mb-3" style="max-height: 400px; width: auto; display: block; margin-left: auto; margin-right: auto; border-radius: 8px;" onerror="this.style.display='none';">
                @endif

                <div class="row text-sm mt-3">
                    <div class="col-md-6">
                        <p><span class="fw-bold">Liên kết đến Phim:</span> {{ $slider->phim->TenPhim ?? '(Không có)' }}</p>
                        <p><span class="fw-bold">URL Liên kết (Nếu không có phim):</span>
                            @if($slider->URL) <a href="{{ $slider->URL }}" target="_blank">{{ $slider->URL }}</a> @else (Trống) @endif
                        </p>
                        <p><span class="fw-bold">Mô tả (SEO):</span> {{ $slider->MoTa }}</p>
                        <p><span class="fw-bold">Từ Khóa (SEO):</span> {{ $slider->TuKhoa }}</p>
                    </div>
                    <div class="col-md-6">
                        {{-- Cột này có vẻ dư thừa? --}}
                        <p><span class="fw-bold">Tên Chủ Đề (?):</span> {{ $slider->TenChuDe }}</p>
                        <p><span class="fw-bold">Người tạo (ID):</span> {{ $slider->NguoiTao ?? 'N/A' }}</p>
                        <p><span class="fw-bold">Người cập nhật (ID):</span> {{ $slider->NguoiCapNhat ?? 'Chưa có' }}</p>
                        <p><span class="fw-bold">Ngày cập nhật:</span> {{ $slider->NgayCapNhat ? $slider->NgayCapNhat->format('d/m/Y H:i') : 'Chưa có' }}</p>
                        <p><span class="fw-bold">Trạng thái:</span>
                            @if ($slider->TrangThai == 1) <span class="badge bg-success">ON</span> @else <span class="badge bg-secondary">OFF</span> @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
