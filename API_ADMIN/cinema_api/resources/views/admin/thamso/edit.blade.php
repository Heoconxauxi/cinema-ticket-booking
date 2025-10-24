@extends('admin.layouts.app')

@section('title', 'Cập nhật Tham Số')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="text-end mb-4"><a class="btn btn-secondary" href="{{ route('admin.thamso.index') }}">Quay lại</a></div>
        
        <div class="card">
            <div class="card-header"><h5>Chỉnh sửa: {{ $thamso->TenThamSo }}</h5></div>
            <div class="card-body">
                <form id="editThamSoForm" action="{{ route('admin.thamso.update', $thamso->Id) }}" method="POST">
                    @csrf 
                    @method('PUT')
                    
                     <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="TenThamSo">Tên Tham Số</label>
                            <input type="text" class="form-control @error('TenThamSo') is-invalid @enderror" id="TenThamSo" name="TenThamSo" value="{{ old('TenThamSo', $thamso->TenThamSo) }}">
                            @error('TenThamSo') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                        </div>
                         <div class="col-md-6 form-group mb-3">
                            <label for="DonViTinh">Đơn Vị Tính</label>
                            <input type="text" class="form-control @error('DonViTinh') is-invalid @enderror" id="DonViTinh" name="DonViTinh" value="{{ old('DonViTinh', $thamso->DonViTinh) }}">
                             @error('DonViTinh') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="GiaTri">Giá Trị</label>
                        <input type="text" class="form-control @error('GiaTri') is-invalid @enderror" id="GiaTri" name="GiaTri" value="{{ old('GiaTri', $thamso->GiaTri) }}">
                        @error('GiaTri') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="TrangThai" name="TrangThai" value="1" {{ old('TrangThai', $thamso->TrangThai) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="TrangThai">Kích hoạt</label>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-gradient-info px-5 mt-3">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection