@extends('admin.layouts.app')

@section('title', 'Cập nhật Suất Chiếu')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="text-end mb-4">
            <a class="btn btn-secondary" href="{{ route('admin.suatchieu.index') }}">
                Quay lại
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Chỉnh sửa Suất Chiếu</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.suatchieu.update', $suatchieu->MaSuatChieu) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="MaPhim">Chọn Phim</label>
                        <select class="form-select @error('MaPhim') is-invalid @enderror" id="MaPhim" name="MaPhim">
                            <option value="">-- Vui lòng chọn phim --</option>
                            @foreach ($phims as $phim)
                                <option value="{{ $phim->MaPhim }}" 
                                    {{ old('MaPhim', $suatchieu->MaPhim) == $phim->MaPhim ? 'selected' : '' }}>
                                    {{ $phim->TenPhim }}
                                </option>
                            @endforeach
                        </select>
                        @error('MaPhim')
                            <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="MaPhong">Chọn Phòng</label>
                        <select class="form-select @error('MaPhong') is-invalid @enderror" id="MaPhong" name="MaPhong">
                            <option value="">-- Vui lòng chọn phòng --</option>
                            @foreach ($phongs as $phong)
                                <option value="{{ $phong->MaPhong }}" 
                                    {{ old('MaPhong', $suatchieu->MaPhong) == $phong->MaPhong ? 'selected' : '' }}>
                                    {{ $phong->TenPhong }}
                                </option>
                            @endforeach
                        </select>
                        @error('MaPhong')
                            <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="GioChieu">Giờ Chiếu</label>
                        {{-- 
                          Format giờ chiếu về 'Y-m-d\TH:i'
                          Đây là định dạng chuẩn mà input datetime-local yêu cầu
                        --}}
                        <input type="datetime-local" 
                               class="form-control @error('GioChieu') is-invalid @enderror" 
                               id="GioChieu" name="GioChieu"
                               value="{{ old('GioChieu', $suatchieu->GioChieu->format('Y-m-d\TH:i')) }}">
                        @error('GioChieu')
                            <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="TrangThai" name="TrangThai" value="1"
                                {{ old('TrangThai', $suatchieu->TrangThai) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="TrangThai">Kích hoạt (Hiển thị)</label>
                        </div>
                    </div>

                    <button type="submit" class="btn bg-gradient-info px-5 mt-3">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection