@extends('layouts.app')
@section('title', 'Đăng ký')

@section('content')
<div class="auth-wrapper">
    <div class="col-md-5">
        <div class="card shadow-lg">
            <div class="card-body p-4">
                <h3 class="text-center text-info mb-4 fw-bold">📝 Tạo Tài Khoản</h3>

                {{-- Hiển thị lỗi --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label text-light">Tên đăng nhập</label>
                        <input type="text" name="TenDangNhap" class="form-control bg-dark text-light border-secondary" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light">Tên người dùng</label>
                        <input type="text" name="TenND" class="form-control bg-dark text-light border-secondary" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light">Mật khẩu</label>
                        <input type="password" name="MatKhau" class="form-control bg-dark text-light border-secondary" required>
                    </div>

                    <button type="submit" class="btn btn-info w-100 mt-2 fw-bold">Đăng ký</button>

                    <p class="text-center mt-3 ">
                             <label class="form-label text-light">Đã có tài khoản?</label>
                        <a href="{{ route('login') }}" class="text-decoration-none text-warning fw-semibold ">Đăng nhập</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
