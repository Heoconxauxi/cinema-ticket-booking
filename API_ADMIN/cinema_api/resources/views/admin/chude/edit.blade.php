@extends('admin.layouts.app')

@section('title', 'Cập nhật Chủ Đề')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="text-end mb-4">
            <a class="btn btn-secondary" href="{{ route('admin.chude.index') }}">
                Quay lại
            </a>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5>Chỉnh sửa: {{ $chude->TenChuDe }}</h5>
            </div>
            <div class="card-body">
                <form id="editChuDeForm" action="{{ route('admin.chude.update', $chude->Id) }}" method="POST">
                    @csrf 
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="TenChuDe">Tên Chủ Đề</label>
                        <input type="text" class="form-control @error('TenChuDe') is-invalid @enderror" 
                               id="TenChuDe" name="TenChuDe"
                               value="{{ old('TenChuDe', $chude->TenChuDe) }}">
                        @error('TenChuDe')
                            <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="MaPhim">Phim Liên Kết (Tùy chọn)</label>
                        <select class="form-select @error('MaPhim') is-invalid @enderror" id="MaPhim" name="MaPhim">
                            <option value="">-- Không chọn phim --</option>
                            @foreach ($phims as $phim)
                                <option value="{{ $phim->MaPhim }}" 
                                    {{ old('MaPhim', $chude->MaPhim) == $phim->MaPhim ? 'selected' : '' }}>
                                    {{ $phim->TenPhim }}
                                </option>
                            @endforeach
                        </select>
                        @error('MaPhim')
                            <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="MoTa">Mô Tả Ngắn</label>
                        <input type="text" class="form-control @error('MoTa') is-invalid @enderror" 
                               id="MoTa" name="MoTa"
                               value="{{ old('MoTa', $chude->MoTa) }}">
                        @error('MoTa')
                            <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="TuKhoa">Từ Khóa</label>
                        <input type="text" class="form-control @error('TuKhoa') is-invalid @enderror" 
                               id="TuKhoa" name="TuKhoa"
                               value="{{ old('TuKhoa', $chude->TuKhoa) }}">
                        @error('TuKhoa')
                            <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="TrangThai" name="TrangThai" value="1"
                                {{ old('TrangThai', $chude->TrangThai) == 1 ? 'checked' : '' }}>
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