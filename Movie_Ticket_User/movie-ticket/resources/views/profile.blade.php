@extends('layouts.app')
@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="container mt-5 text-white">

    <h2 class="mb-4 text-center">👤 Hồ Sơ Cá Nhân</h2>

    <div class="card bg-dark p-4 rounded">
        <div class="d-flex align-items-center mb-3">
            <img src="{{ $user['nguoidung']['Anh'] ?? 'https://i.pinimg.com/originals/0a/bd/3a/0abd3a0576f39f5a6b6f8ed0a5095b5d.png' }}"
                width="100" height="100" class="rounded-circle border border-light me-3" alt="Avatar">
            <div>
                <h4>{{ $user['TenND'] ?? 'Người dùng' }}</h4>
                <p class="mb-0 text-muted">ID: {{ $MaND }}</p>
            </div>
        </div>

        {{-- Thông báo --}}
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Nút tải dữ liệu từ API --}}
        <div class="d-flex gap-2 mb-3 align-items-center">
            <input
                type="number"
                id="user_id"
                class="form-control w-25 text-muted"
                value="{{ $MaND }}"
                readonly
                style="background-color: #444; opacity: 0.6; cursor: not-allowed;">
            <button id="btn-fetch" class="btn btn-primary">🔄 Lấy Thông Tin</button>
        </div>


        <form id="update-form">
            <h5 class="text-warning">Thông Tin Tài Khoản</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="text-white">Tên đăng nhập</label>
                    <input type="text" id="TenDangNhap" name="TenDangNhap" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-white">Tên người dùng</label>
                    <input type="text" id="TenND" name="TenND" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-white">Mật khẩu mới</label>
                    <input type="password" id="MatKhau" name="MatKhau" class="form-control" placeholder="Để trống nếu không đổi">
                </div>
            </div>

            <h5 class="text-warning mt-3">Thông Tin Người Dùng</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="text-white">Email</label>
                    <input type="email" id="Email" name="Email" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-white">Ngày sinh</label>
                    <input type="date" id="NgaySinh" name="NgaySinh" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-white">Số điện thoại</label>
                    <input type="text" id="SDT" name="SDT" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="text-white">Ảnh (URL)</label>
                    <input type="text" id="Anh" name="Anh" class="form-control">
                </div>
            </div>

            <button type="submit" id="btn-submit" class="btn btn-success mt-3" disabled>
                💾 Cập nhật thông tin
            </button>
        </form>

        <div class="mt-4">
            <h5>Kết quả API</h5>
            <pre id="response" class="bg-secondary text-white p-3 rounded">(Chờ thao tác...)</pre>
        </div>
    </div>
</div>

{{-- 🧠 Script --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const API_BASE = "{{ $apiBase }}";
        const form = document.getElementById('update-form');
        const btnFetch = document.getElementById('btn-fetch');
        const btnSubmit = document.getElementById('btn-submit');
        const userIdInput = document.getElementById('user_id');
        const responseEl = document.getElementById('response');
        let currentUserId = "{{ $MaND }}";

        // === Lấy dữ liệu người dùng ===
        btnFetch.addEventListener('click', async () => {
            currentUserId = userIdInput.value;
            if (!currentUserId) return alert('Vui lòng nhập MaND.');

            responseEl.textContent = `Đang tải dữ liệu cho MaND: ${currentUserId}...`;
            try {
                const res = await fetch(`${API_BASE}/taikhoan/${currentUserId}`);
                const result = await res.json();
                if (!result.success) throw new Error(result.message || 'Không tìm thấy user.');

                const taiKhoan = result.data;
                const nguoiDung = result.data.nguoidung;

                document.getElementById('TenDangNhap').value = taiKhoan.TenDangNhap ?? '';
                document.getElementById('TenND').value = taiKhoan.TenND ?? '';

                if (nguoiDung) {
                    document.getElementById('Email').value = nguoiDung.Email ?? '';
                    document.getElementById('NgaySinh').value = nguoiDung.NgaySinh ? nguoiDung.NgaySinh.split('T')[0] : '';
                    document.getElementById('SDT').value = nguoiDung.SDT ?? '';
                    document.getElementById('Anh').value = nguoiDung.Anh ?? '';
                }

                btnSubmit.disabled = false;
                responseEl.textContent = JSON.stringify(result, null, 2);
            } catch (err) {
                btnSubmit.disabled = true;
                responseEl.textContent = '❌ ' + err.message;
            }
        });

        // === Gửi cập nhật ===
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!currentUserId) return alert('Hãy nhập và lấy thông tin trước.');

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());
            if (!data.MatKhau) delete data.MatKhau;

            responseEl.textContent = '⏳ Đang gửi cập nhật...';
            try {
                const res = await fetch(`${API_BASE}/taikhoan/${currentUserId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                responseEl.textContent = JSON.stringify(result, null, 2);
                alert(result.message || 'Cập nhật thành công!');
            } catch (err) {
                responseEl.textContent = '❌ ' + err.message;
            }
        });
    });
</script>
@endsection