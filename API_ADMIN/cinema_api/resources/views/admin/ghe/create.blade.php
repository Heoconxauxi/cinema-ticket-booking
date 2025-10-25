@extends('admin.layouts.app')

@section('title', 'Thêm mới Ghế')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        
        <div class="text-end mb-4">
            <a class="btn btn-secondary" href="{{ route('admin.ghe.index') }}">
                Quay lại
            </a>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5>Thêm Ghế Mới</h5>
            </div>
            <div class="card-body">
                <form id="addGheForm" action="{{ route('admin.ghe.store') }}" method="POST">
                    @csrf 

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="MaPhong">Chọn Phòng</label>
                                <select class="form-select @error('MaPhong') is-invalid @enderror" id="MaPhong" name="MaPhong">
                                    <option value="">-- Vui lòng chọn phòng --</option>
                                    @foreach ($phongs as $phong)
                                        <option value="{{ $phong->MaPhong }}" {{ old('MaPhong') == $phong->MaPhong ? 'selected' : '' }}>
                                            {{ $phong->TenPhong }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('MaPhong')
                                    <small class="text-danger m-2 text-xs">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="TenGhe">Tên Ghế</label>
                                <input type="text" class="form-control @error('TenGhe') is-invalid @enderror" 
                                       id="TenGhe" name="TenGhe"
                                       value="{{ old('TenGhe') }}" placeholder="Ví dụ: A1, B2, C10...">
                                @error('TenGhe')
                                    <small class="text-danger m-2 text-xs">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                             <div class="form-group mb-3">
                                <label for="LoaiGhe">Loại Ghế</label>
                                <select class="form-select @error('LoaiGhe') is-invalid @enderror" id="LoaiGhe" name="LoaiGhe">
                                    <option value="">-- Vui lòng chọn loại ghế --</option>
                                    <option value="Đơn" {{ old('LoaiGhe') == 'Đơn' ? 'selected' : '' }}>Đơn (Giá: {{ number_format($giaDon ?? 0) }} VNĐ)</option>
                                    <option value="Đôi" {{ old('LoaiGhe') == 'Đôi' ? 'selected' : '' }}>Đôi (Giá: {{ number_format($giaDoi ?? 0) }} VNĐ)</option>
                                    <option value="VIP" {{ old('LoaiGhe') == 'VIP' ? 'selected' : '' }}>VIP (Giá: {{ number_format($giaVip ?? 0) }} VNĐ)</option>
                                </select>
                                @error('LoaiGhe')
                                    <small class="text-danger m-2 text-xs">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="GiaGhe">Giá Ghế</label>
                                <input type="number" class="form-control @error('GiaGhe') is-invalid @enderror" 
                                       id="GiaGhe" name="GiaGhe"
                                       value="{{ old('GiaGhe') }}" placeholder="Nhập giá (ví dụ: 79000)">
                                @error('GiaGhe')
                                    <small class="text-danger m-2 text-xs">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="TrangThai" name="TrangThai" value="1" checked>
                            <label class="form-check-label" for="TrangThai">Kích hoạt (Hiển thị)</label>
                        </div>
                    </div>

                    <button type="submit" class="btn bg-gradient-info px-5 mt-3">Lưu</button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection