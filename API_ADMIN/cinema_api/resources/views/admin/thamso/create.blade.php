@extends('admin.layouts.app')

@section('title', 'Thêm mới Tham Số')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="text-end mb-4"><a class="btn btn-secondary" href="{{ route('admin.thamso.index') }}">Quay lại</a></div>
        
        <div class="card">
            <div class="card-header"><h5>Thêm Tham Số Mới</h5></div>
            <div class="card-body">
                <form id="addThamSoForm" action="{{ route('admin.thamso.store') }}" method="POST">
                    @csrf 
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="TenThamSo">Tên Tham Số</label>
                            <input type="text" class="form-control @error('TenThamSo') is-invalid @enderror" id="TenThamSo" name="TenThamSo" value="{{ old('TenThamSo') }}" placeholder="Ví dụ: Đơn, Đôi, VIP, Silver...">
                            @error('TenThamSo') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                        </div>
                         <div class="col-md-6 form-group mb-3">
                            <label for="DonViTinh">Đơn Vị Tính (Nếu có)</label>
                            <input type="text" class="form-control @error('DonViTinh') is-invalid @enderror" id="DonViTinh" name="DonViTinh" value="{{ old('DonViTinh') }}" placeholder="Ví dụ: VNĐ, %...">
                             @error('DonViTinh') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="GiaTri">Giá Trị</label>
                        <input type="text" class="form-control @error('GiaTri') is-invalid @enderror" id="GiaTri" name="GiaTri" value="{{ old('GiaTri') }}" placeholder="Nhập giá trị của tham số">
                        @error('GiaTri') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="TrangThai" name="TrangThai" value="1" checked>
                            <label class="form-check-label" for="TrangThai">Kích hoạt</label>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-gradient-info px-5 mt-3">Lưu</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection