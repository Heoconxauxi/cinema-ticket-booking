@extends('admin.layouts.app')

@section('title', 'Cập nhật Phòng Chiếu')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        
        <div class="text-end mb-4">
            <a class="btn btn-secondary" href="{{ route('admin.phong.index') }}">
                Quay lại
            </a>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Chỉnh sửa: {{ $phong->TenPhong }}</h5>
            </div>
            <div class="card-body">
                <form id="editPhongForm" action="{{ route('admin.phong.update', $phong->MaPhong) }}" method="POST">
                    @csrf
                    @method('PUT') 

                    <div class="form-group mb-3">
                        <label for="TenPhong">Tên Phòng</label>
                        <input type="text" class="form-control @error('TenPhong') is-invalid @enderror" 
                               id="TenPhong" name="TenPhong"
                               value="{{ old('TenPhong', $phong->TenPhong) }}">
                        
                        @error('TenPhong')
                            <small class="text-danger m-2 text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="TrangThai" name="TrangThai" value="1"
                                {{ old('TrangThai', $phong->TrangThai) == 1 ? 'checked' : '' }}>
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