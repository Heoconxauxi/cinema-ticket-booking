@extends('layouts.app')
@section('title', 'Thanh toán vé')

@section('content')
<div class="container mt-5 text-center text-white">
    <h2 class="text-warning mb-3">💳 Xác nhận thanh toán</h2>
    <div id="info"></div>

    <button class="btn btn-success mt-4" onclick="xacNhan()">✅ Thanh toán ngay</button>
</div>

<script>
    // Định nghĩa các biến toàn cục
    const API_URL = "http://127.0.0.1:8000/api";
    // Cần CSRF token để gửi POST request trong Laravel
    const CSRF_TOKEN = "{{ csrf_token() }}";

    // Lấy MaND từ session (được render bởi Blade)
    // 🛑 QUAN TRỌNG: Phải chắc chắn bạn đã lưu 'MaND' vào session khi đăng nhập
    const MA_ND = "{{ session('MaND') }}"; // Trả về chuỗi hoặc rỗng nếu chưa đăng nhập


    document.addEventListener('DOMContentLoaded', () => {
        // Lấy dữ liệu từ sessionStorage
        const gheIds = JSON.parse(sessionStorage.getItem('selectedSeats') || '[]');
        const tongTien = sessionStorage.getItem('tongTien') || 0;
        const phim = JSON.parse(sessionStorage.getItem('phim') || '{}');
        const suat = JSON.parse(sessionStorage.getItem('suat') || '{}');

        // Hiển thị thông tin (Code của bạn đã đúng)
        document.getElementById('info').innerHTML = `
            <p><strong>🎬 Phim:</strong> ${phim.TenPhim}</p>
            <p><strong>🕒 Giờ chiếu:</strong> ${new Date(suat.GioChieu).toLocaleString('vi-VN')}</p>
            <p><strong>🏢 Phòng:</strong> ${suat.phong?.TenPhong || 'Rạp chưa rõ'}</p>
            <p><strong>💺 Ghế chọn:</strong> ${gheIds.join(', ')}</p>
            <p><strong>💰 Tổng tiền:</strong> <span class="text-info">${parseInt(tongTien).toLocaleString('vi-VN')} đ</span></p>
        `;

        // Kiểm tra đăng nhập
        if (!MA_ND) {
            document.getElementById('info').innerHTML += '<p class="text-danger fw-bold">Lỗi: Bạn chưa đăng nhập. Vui lòng quay lại và đăng nhập.</p>';
            document.querySelector('button').disabled = true;
        }
    });

    // 🔥 HÀM XÁC NHẬN (ĐÃ VIẾT LẠI)
    async function xacNhan() {
        const gheIds = JSON.parse(sessionStorage.getItem('selectedSeats') || '[]');
        const tongTien = parseInt(sessionStorage.getItem('tongTien') || 0);
        const maSuatChieu = parseInt(sessionStorage.getItem('maSuatChieu'));

        if (!MA_ND || gheIds.length === 0 || !maSuatChieu) {
            alert("Lỗi: Thông tin đặt vé không hợp lệ hoặc bạn chưa đăng nhập.");
            return;
        }

        // 1. Chuẩn bị mảng 'details' theo yêu cầu của API HoaDonController@store
        const details = gheIds.map(gheId => {
            return {
                MaSuatChieu: maSuatChieu,
                MaGhe: parseInt(gheId)
            };
        });

        // 2. Chuẩn bị toàn bộ body
        const hoaDonData = {
            MaND: MA_ND,
            TongTien: tongTien,
            NguoiTao: MA_ND, // Người tạo hóa đơn chính là người dùng
            details: details
        };

        // Vô hiệu hóa nút để tránh click đúp
        document.querySelector('button').disabled = true;
        document.querySelector('button').textContent = "Đang xử lý...";

        // 3. Gọi API
        try {
            const response = await fetch(`${API_URL}/hoadon`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    // 'X-CSRF-TOKEN': CSRF_TOKEN // Thường không cần cho API, nhưng cứ để
                },
                body: JSON.stringify(hoaDonData)
            });

            const result = await response.json();

            if (result.success) {
                // Thành công!
                alert("🎉 Đặt vé thành công! Cảm ơn bạn.");

                // Xóa lock ghế (dọn dẹp)
                const clientId = sessionStorage.getItem('clientId');
                gheIds.forEach(id => {
                    if (localStorage.getItem(`lock_${id}`) === clientId) {
                        localStorage.removeItem(`lock_${id}`);
                    }
                });

                sessionStorage.clear(); // Xóa data tạm
                window.location.href = '/'; // Về trang chủ
            } else {
                // Lỗi từ API (ghế đã bị đặt, validation...)
                console.error('Lỗi API:', result.errors || result.message);
                alert(`Đặt vé thất bại: ${result.message || JSON.stringify(result.errors)}`);
                document.querySelector('button').disabled = false;
                document.querySelector('button').textContent = "✅ Thanh toán ngay";
            }

        } catch (error) {
            console.error('Lỗi fetch:', error);
            alert("Đã xảy ra lỗi kết nối. Vui lòng thử lại.");
            document.querySelector('button').disabled = false;
            document.querySelector('button').textContent = "✅ Thanh toán ngay";
        }
    }
</script>
@endsection