@extends('admin.layouts.app')

@section('title', 'Cập nhật Tài Khoản')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="text-end mb-4"><a class="btn btn-secondary" href="{{ route('admin.taikhoan.index') }}">Quay lại</a></div>
        
        <div class="card">
            <div class="card-header"><h5>Chỉnh sửa: {{ $taikhoan->TenDangNhap }}</h5></div>
            <div class="card-body">
                <form id="editTaiKhoanForm" action="{{ route('admin.taikhoan.update', $taikhoan->MaND) }}" method="POST">
                    @csrf 
                    @method('PUT')
                    
                    <div class="form-group mb-3">
                        <label for="TenDangNhap">Tên Đăng Nhập</label>
                        <input type="text" class="form-control @error('TenDangNhap') is-invalid @enderror" id="TenDangNhap" name="TenDangNhap" value="{{ old('TenDangNhap', $taikhoan->TenDangNhap) }}" required>
                        @error('TenDangNhap') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="MatKhau">Mật Khẩu Mới (Để trống nếu không đổi)</label>
                            <input type="password" class="form-control @error('MatKhau') is-invalid @enderror" id="MatKhau" name="MatKhau">
                            @error('MatKhau') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="MatKhau_confirmation">Xác nhận Mật Khẩu Mới</label>
                            <input type="password" class="form-control" id="MatKhau_confirmation" name="MatKhau_confirmation">
                        </div>
                    </div>
                     <div class="form-group mb-3">
                        <label for="TenND">Tên Người Dùng (Hiển thị)</label>
                        <input type="text" class="form-control @error('TenND') is-invalid @enderror" id="TenND" name="TenND" value="{{ old('TenND', $taikhoan->TenND) }}" required>
                        @error('TenND') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="Quyen">Quyền</label>
                        <select class="form-select @error('Quyen') is-invalid @enderror" id="Quyen" name="Quyen" required>
                             @foreach($roles as $key => $value)
                            <option value="{{ $key }}" {{ old('Quyen', $taikhoan->Quyen) == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('Quyen') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                    </div>
                    
                    <button type="submit" class="btn bg-gradient-info px-5 mt-3">Lưu thay đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection