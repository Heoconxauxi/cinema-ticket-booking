@extends('admin.layouts.app')

@section('title', 'Chi tiết Hóa Đơn #' . $hoadon->MaHD)

@section('content')
<div class="row">
    <div class="col-xl-10 col-lg-12 mx-auto">
        <div class="text-end mb-4">
            {{-- No Edit button needed usually --}}
            <a class="btn btn-secondary" href="{{ route('admin.hoadon.index') }}">Quay lại Danh sách</a>
        </div>

        <div class="card">
            <div class="card-header pb-0">
                <h4 class="mb-1">Chi tiết Hóa Đơn #{{ $hoadon->MaHD }}</h4>
                 <p class="text-sm mb-2">
                    <span class="fw-bold">Người đặt:</span> {{ $hoadon->nguoiDung->TenND ?? 'N/A' }} ({{ $hoadon->nguoiDung->taiKhoan->TenDangNhap ?? 'N/A' }}) | 
                    <span class="fw-bold">Ngày lập:</span> {{ $hoadon->NgayLapHD->format('d/m/Y H:i') }} |
                    <span class="fw-bold">Tổng tiền:</span> <span class="text-danger fw-bold">{{ number_format($hoadon->TongTien, 0, ',', '.') }} VNĐ</span> |
                    <span class="fw-bold">Trạng thái:</span> 
                    @if ($hoadon->TrangThai == 1) <span class="badge bg-success">Đã Thanh Toán</span> @else <span class="badge bg-warning text-dark">Chưa Thanh Toán</span> @endif
                </p>
            </div>
            <hr class="mt-0 mb-1">
            <div class="card-body pt-2">
                <h5>Danh sách Vé / Chi Tiết</h5>
                @if($hoadon->chiTietHoaDons->isEmpty())
                    <p class="text-muted">Hóa đơn này không có chi tiết nào.</p>
                @else
                    <div class="table-responsive p-0">
                        <table class="table table-sm table-bordered align-items-center mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Phim</th>
                                    <th>Phòng</th>
                                    <th>Giờ Chiếu</th>
                                    <th>Ghế</th>
                                    <th>Loại Ghế</th>
                                    <th>Giá Ghế</th>
                                    <th>Ngày Đặt Vé</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hoadon->chiTietHoaDons as $cthd)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    {{-- Access nested relationships safely using optional() or ?? --}}
                                    <td>{{ $cthd->suatChieu->phim->TenPhim ?? 'N/A' }}</td>
                                    <td>{{ $cthd->suatChieu->phong->TenPhong ?? 'N/A' }}</td>
                                    <td>{{ $cthd->suatChieu->GioChieu->format('H:i d/m/Y') ?? 'N/A' }}</td>
                                    <td>{{ $cthd->ghe->TenGhe ?? 'N/A' }}</td>
                                    <td>{{ $cthd->ghe->LoaiGhe ?? 'N/A' }}</td>
                                    <td>{{ number_format($cthd->ghe->GiaGhe ?? 0, 0, ',', '.') }}</td>
                                    <td>{{ $cthd->NgayTao->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection