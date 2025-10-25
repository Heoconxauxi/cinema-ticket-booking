@extends('layouts.app')
@section('title', 'Đăng nhập')

@section('content')
<div class="auth-wrapper">
    <div class="col-md-5">
        <div class="card shadow-lg">
            <div class="card-body p-4">
                <h3 class="text-center text-warning mb-4 fw-bold">🔑 Đăng Nhập</h3>

                {{-- Thông báo lỗi --}}
                @if(session('error'))
                    <div class="alert alert-danger text-center">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success text-center">{{ session('success') }}</div>
                @endif

                {{-- Form đăng nhập --}}
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-light">Tên đăng nhập</label>
                        <input type="text" name="TenDangNhap" class="form-control bg-dark text-light border-secondary" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-light">Mật khẩu</label>
                        <input type="password" name="MatKhau" class="form-control bg-dark text-light border-secondary" required>
                    </div>  

                    <button type="submit" class="btn btn-warning w-100 mt-2 fw-bold">Đăng nhập</button>

                    <p class="text-center mt-3">
                      <label class="form-label text-light">Chưa có tài khoản?</label>
                        <a href="{{ route('register') }}" class="text-decoration-none text-info fw-semibold">Đăng ký ngay</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
