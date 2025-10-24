@extends('admin.layouts.app')

@section('title', 'Thêm mới Tài Khoản')

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="text-end mb-4"><a class="btn btn-secondary" href="{{ route('admin.taikhoan.index') }}">Quay lại</a></div>
        
        <div class="alert alert-warning text-white" role="alert">
            <strong>Cảnh báo:</strong> Việc tạo tài khoản ở đây sẽ KHÔNG tự động tạo hồ sơ người dùng tương ứng. Nên sử dụng chức năng "Thêm Người Dùng" để đảm bảo tính nhất quán.
        </div>

        <div class="card">
            <div class="card-header"><h5>Thêm Tài Khoản Mới</h5></div>
            <div class="card-body">
                <form id="addTaiKhoanForm" action="{{ route('admin.taikhoan.store') }}" method="POST">
                    @csrf 
                     <div class="form-group mb-3">
                        <label for="TenDangNhap">Tên Đăng Nhập</label>
                        <input type="text" class="form-control @error('TenDangNhap') is-invalid @enderror" id="TenDangNhap" name="TenDangNhap" value="{{ old('TenDangNhap') }}" required>
                        @error('TenDangNhap') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="MatKhau">Mật Khẩu</label>
                            <input type="password" class="form-control @error('MatKhau') is-invalid @enderror" id="MatKhau" name="MatKhau" required>
                            @error('MatKhau') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="MatKhau_confirmation">Xác nhận Mật Khẩu</label>
                            <input type="password" class="form-control" id="MatKhau_confirmation" name="MatKhau_confirmation" required>
                        </div>
                    </div>
                     <div class="form-group mb-3">
                        <label for="TenND">Tên Người Dùng (Hiển thị)</label>
                        <input type="text" class="form-control @error('TenND') is-invalid @enderror" id="TenND" name="TenND" value="{{ old('TenND') }}" required>
                        @error('TenND') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="Quyen">Quyền</label>
                        <select class="form-select @error('Quyen') is-invalid @enderror" id="Quyen" name="Quyen" required>
                            @foreach($roles as $key => $value)
                            <option value="{{ $key }}" {{ old('Quyen') == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('Quyen') <small class="text-danger m-2 text-xs">{{ $message }}</small> @enderror
                    </div>
                    
                    <button type="submit" class="btn bg-gradient-info px-5 mt-3">Thêm Tài Khoản</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection