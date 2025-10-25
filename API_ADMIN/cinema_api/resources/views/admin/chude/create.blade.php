@extends('admin.layouts.app')

@section('title', 'Thêm mới Chủ Đề')

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
                <h5>Thêm Chủ Đề Mới</h5>
            </div>
            <div class="card-body">
                <form id="addChuDeForm" action="{{ route('admin.chude.store') }}" method="POST">
                    @csrf 

                    <div class="form-group mb-3">
                        <label for="TenChuDe">Tên Chủ Đề</label>
                        <input type="text" class="form-control @error('TenChuDe') is-invalid @enderror" 
                               id="TenChuDe" name="TenChuDe"
                               value="{{ old('TenChuDe') }}" placeholder="Ví dụ: Blog Điện Ảnh, Khuyến Mãi...">
                        @error('TenChuDe')
                            <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="MaPhim">Phim Liên Kết (Tùy chọn)</label>
                        <select class="form-select @error('MaPhim') is-invalid @enderror" id="MaPhim" name="MaPhim">
                            <option value="">-- Không chọn phim --</option>
                            @foreach ($phims as $phim)
                                <option value="{{ $phim->MaPhim }}" {{ old('MaPhim') == $phim->MaPhim ? 'selected' : '' }}>
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
                               value="{{ old('MoTa') }}" placeholder="Mô tả ngắn (SEO)">
                        @error('MoTa')
                            <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="TuKhoa">Từ Khóa</label>
                        <input type="text" class="form-control @error('TuKhoa') is-invalid @enderror" 
                               id="TuKhoa" name="TuKhoa"
                               value="{{ old('TuKhoa') }}" placeholder="Từ khóa (SEO), cách nhau bằng dấu phẩy">
                        @error('TuKhoa')
                            <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
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