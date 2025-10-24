@extends('admin.layouts.app')

@section('title', 'Chi tiết phim: ' . $phim->TenPhim)

@section('content')
<div class="row">
    <div class="col-xl-12 col-lg-12 mx-auto">
        <div class="text-end mb-4">
            <a class="btn btn-info" href="{{ route('admin.phim.edit', $phim->MaPhim) }}">
                <i class="bi bi-pencil me-2"></i>Sửa
            </a>
            <a class="btn btn-secondary" href="{{ route('admin.phim.index') }}">
                Quay lại
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3"> <label class="fs-6 fw-bold">Mã phim:</label> <span>{{ $phim->MaPhim }}</span> </div>
                        <div class="mb-3"> <label class="fs-6 fw-bold">Tên phim:</label> <span>{{ $phim->TenPhim }}</span> </div>
                        <div class="mb-3"> <label class="fs-6 fw-bold">Năm phát hành:</label> <span>{{ $phim->NamPhatHanh }}</span> </div>
                        <div class="mb-3"> <label class="fs-6 fw-bold">Thời lượng:</label> <span>{{ $phim->ThoiLuong }} phút</span> </div>
                        <div class="mb-3"> <label class="fs-6 fw-bold">Quốc gia:</label> <span>{{ $phim->QuocGia ?? 'Chưa xác định' }}</span> </div>
                        <div class="mb-3"> <label class="fs-6 fw-bold">Đạo diễn:</label> <span>{{ $phim->DaoDien ?? 'Chưa xác định' }}</span> </div>
                        <div class="mb-3"> <label class="fs-6 fw-bold">Diễn viên:</label> <span>{{ $phim->DienVien ?? 'Chưa xác định' }}</span> </div>
                        <div class="mb-3"> <label class="fs-6 fw-bold">Phân loại:</label> <span>{{ $phim->PhanLoai ?? 'Chưa xác định' }}</span> </div>
                        {{-- Ảnh Poster --}}
                        <div class="mb-3">
                            <label class="fs-6 fw-bold d-block">Ảnh Poster:</label>
                            {{-- Lấy thẳng URL từ $phim->Anh --}}
                            @if ($phim->Anh)
                            <img src="{{ $phim->Anh }}" alt="Ảnh phim" class="img-fluid" style="max-height: 200px; border-radius: 8px;" onerror="this.style.display='none'; this.previousElementSibling.textContent = 'Ảnh Poster: Lỗi tải ảnh';">
                            @else
                            <span>Chưa có ảnh</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3"> <label class="fs-6 fw-bold">Thể loại:</label> <span>{{ $phim->theLoais->pluck('TenTheLoai')->implode(', ') ?: 'Chưa xác định' }}</span> </div>
                        {{-- Hiển thị tên người tạo/cập nhật qua quan hệ --}}
                        <div class="mb-3"> <label class="fs-6 fw-bold">Người tạo:</label> <span>{{ $phim->nguoiTao->TenND ?? ($phim->NguoiTao == 0 ? 'Hệ thống' : 'Không rõ') }}</span> </div>
                        <div class="mb-3"> <label class="fs-6 fw-bold">Ngày tạo:</label> <span>{{ $phim->NgayTao ? $phim->NgayTao->format('d/m/Y H:i:s') : 'Chưa xác định' }}</span> </div>
                        <div class="mb-3"> <label class="fs-6 fw-bold">Người cập nhật:</label> <span>{{ $phim->nguoiCapNhat->TenND ?? 'Chưa có' }}</span> </div>
                        <div class="mb-3"> <label class="fs-6 fw-bold">Ngày cập nhật:</label> <span>{{ $phim->NgayCapNhat ? $phim->NgayCapNhat->format('d/m/Y H:i:s') : 'Chưa có' }}</span> </div>
                        <div class="mb-3"> <label class="fs-6 fw-bold">Trạng thái:</label> <span> @if ($phim->TrangThai == 1) <span class="badge bg-success">ON</span> @elseif ($phim->TrangThai == 2) <span class="badge bg-warning text-dark">COMING SOON</span> @else <span class="badge bg-secondary">OFF</span> @endif </span> </div>
                        {{-- Ảnh Banner --}}
                        <div class="mb-3">
                            <label class="fs-6 fw-bold d-block">Ảnh Banner:</label>
                            {{-- Lấy thẳng URL từ $phim->Banner --}}
                            @if ($phim->Banner)
                            <img src="{{ $phim->Banner }}" alt="Ảnh banner" class="img-fluid" style="max-height: 150px; border-radius: 8px;" onerror="this.style.display='none'; this.previousElementSibling.textContent = 'Ảnh Banner: Lỗi tải ảnh';">
                            @else
                            <span>Chưa có banner</span>
                            @endif
                        </div>
                        <div class="mb-3"> <label class="fs-6 fw-bold">Mô tả:</label>
                            <p style="white-space: pre-wrap;">{{ $phim->MoTa }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
