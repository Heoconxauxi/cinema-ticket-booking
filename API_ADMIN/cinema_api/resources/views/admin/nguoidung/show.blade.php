@extends('admin.layouts.app')

@section('title', 'Chi tiết Người Dùng: ' . $nguoidung->TenND)

@section('content')
<div class="row">
    <div class="col-xl-10 col-lg-12 mx-auto">
        <div class="text-end mb-4">
            <a class="btn btn-info" href="{{ route('admin.nguoidung.edit', $nguoidung->MaND) }}">Sửa</a>
            <a class="btn btn-secondary" href="{{ route('admin.nguoidung.index') }}">Quay lại</a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    {{-- Left: Avatar and Account Info --}}
                    <div class="col-md-4 text-center">
                        {{-- Đặt đường dẫn đến ảnh đại diện mặc định của bạn --}}
                        @php $defaultAvatarUrl = 'https://static.thenounproject.com/png/363640-200.png'; @endphp
                        <img 
                            src="{{ $nguoidung->Anh ?: $defaultAvatarUrl }}" 
                            alt="Avatar" 
                            class="avatar me-3 border rounded-circle" {{-- Bỏ class avatar-sm, thêm rounded-circle --}}
                            style="width: 18px; height: 18px; object-fit: cover;" {{-- Set kích thước nhỏ hơn --}}
                            onerror="this.onerror=null; this.src='{{ $defaultAvatarUrl }}';">
                        <h4 class="mb-0">{{ $nguoidung->TenND }}</h4>
                        <p class="text-muted text-sm">{{ $nguoidung->taiKhoan->TenDangNhap ?? 'Lỗi Username' }}</p>
                        <p>
                            <span class="fw-bold">Quyền:</span>
                            @php
                            $roleName = match($nguoidung->taiKhoan->Quyen ?? -1) {
                            0 => 'Người dùng',
                            1 => 'Admin',
                            2 => 'Nhân viên',
                            default => 'Không xác định'
                            };
                            @endphp
                            <span class="badge bg-dark text-white rounded-pill px-3 py-2">{{ $roleName }}</span>
                        </p>
                        <p>
                            <span class="fw-bold">Trạng thái:</span>
                            @if ($nguoidung->TrangThai == 1) <span class="badge bg-success">Hoạt động</span> @else <span class="badge bg-secondary">Bị khóa</span> @endif
                        </p>
                    </div>
                    {{-- Right: Profile Details --}}
                    <div class="col-md-8">
                        <h5>Thông tin chi tiết</h5>
                        <hr class="mt-1 mb-3">
                        <div class="row text-sm">
                            <div class="col-md-6">
                                <p><span class="fw-bold">Mã Người Dùng:</span> {{ $nguoidung->MaND }}</p>
                                <p><span class="fw-bold">Họ và Tên:</span> {{ $nguoidung->TenND }}</p>
                                <p><span class="fw-bold">Email:</span> {{ $nguoidung->Email ?? '(Chưa có)' }}</p>
                                <p><span class="fw-bold">Số Điện Thoại:</span> {{ $nguoidung->SDT ?? '(Chưa có)' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><span class="fw-bold">Ngày Sinh:</span> {{ $nguoidung->NgaySinh ? $nguoidung->NgaySinh->format('d/m/Y') : '(Chưa có)' }}</p>
                                <p><span class="fw-bold">Giới Tính:</span>
                                    @if($nguoidung->GioiTinh === 1) Nam
                                    @elseif($nguoidung->GioiTinh === 0) Nữ
                                    @else (Chưa có)
                                    @endif
                                </p>
                                <p><span class="fw-bold">Ngày Tạo:</span> {{ $nguoidung->NgayTao ? $nguoidung->NgayTao->format('d/m/Y H:i') : 'N/A' }}</p>
                                <p><span class="fw-bold">Cập Nhật Lần Cuối:</span> {{ $nguoidung->NgayCapNhat ? $nguoidung->NgayCapNhat->format('d/m/Y H:i') : 'Chưa có' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
