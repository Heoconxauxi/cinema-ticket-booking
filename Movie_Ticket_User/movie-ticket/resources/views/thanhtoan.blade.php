@extends('layouts.app')
@section('title', 'Thanh toán vé')

@section('content')
<div class="container mt-5 text-center text-white">
    <h2 class="text-warning mb-3">💳 Xác nhận thanh toán</h2>
    <div id="info"></div>

    <!-- Stripe zone -->
    <div id="payment-element" class="my-4"></div>
    <button id="pay-btn" class="btn btn-success w-100">💳 Thanh toán ngay</button>
    <div id="result-msg" class="mt-3 text-info"></div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
const STRIPE_KEY = "{{ env('STRIPE_PUBLIC') }}";
const stripe = Stripe(STRIPE_KEY);
const MA_ND = "{{ session('MaND') }}"; // người dùng
const CSRF_TOKEN = "{{ csrf_token() }}";

document.addEventListener('DOMContentLoaded', async () => {
    // === Hiển thị thông tin vé ===
    const gheIds = JSON.parse(sessionStorage.getItem('selectedSeats') || '[]');
    const tongTien = parseInt(sessionStorage.getItem('tongTien') || 0);
    const phim = JSON.parse(sessionStorage.getItem('phim') || '{}');
    const suat = JSON.parse(sessionStorage.getItem('suat') || '{}');
    const maSuatChieu = parseInt(sessionStorage.getItem('maSuatChieu'));

    document.getElementById('info').innerHTML = `
        <p><strong>🎬 Phim:</strong> ${phim.TenPhim || 'Không rõ'}</p>
        <p><strong>🕒 Giờ chiếu:</strong> ${suat.GioChieu ? new Date(suat.GioChieu).toLocaleString('vi-VN') : 'Không rõ'}</p>
        <p><strong>🏢 Phòng:</strong> ${suat.phong?.TenPhong || 'Rạp chưa rõ'}</p>
        <p><strong>💺 Ghế chọn:</strong> ${gheIds.join(', ')}</p>
        <p><strong>💰 Tổng tiền:</strong> <span class="text-info">${tongTien.toLocaleString('vi-VN')} đ</span></p>
    `;

    if (!MA_ND) {
        document.getElementById('info').innerHTML += '<p class="text-danger fw-bold">⚠️ Bạn chưa đăng nhập!</p>';
        document.getElementById('pay-btn').disabled = true;
        return;
    }

    // === Tạo PaymentIntent trực tiếp (local) ===
    const response = await fetch("/stripe-intent", {
        method: "POST",
        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": CSRF_TOKEN },
        body: JSON.stringify({ amount: tongTien })
    });
    const { clientSecret } = await response.json();

    const elements = stripe.elements({ clientSecret });
    const paymentElement = elements.create("payment");
    paymentElement.mount("#payment-element");

    // === Khi người dùng nhấn thanh toán ===
    document.getElementById("pay-btn").addEventListener("click", async () => {
        document.getElementById("pay-btn").disabled = true;
        document.getElementById("pay-btn").textContent = "🔄 Đang xử lý...";

        const { error, paymentIntent } = await stripe.confirmPayment({
            elements,
            redirect: "if_required"
        });

        if (error) {
            document.getElementById("result-msg").innerText = "❌ " + error.message;
            document.getElementById("pay-btn").disabled = false;
            document.getElementById("pay-btn").textContent = "💳 Thanh toán ngay";
            return;
        }

        if (paymentIntent && paymentIntent.status === "succeeded") {
            // === Thanh toán thành công => Lưu hóa đơn như cũ ===
            const details = gheIds.map(id => ({ MaSuatChieu: maSuatChieu, MaGhe: parseInt(id) }));
            const hoaDonData = {
                MaND: MA_ND,
                TongTien: tongTien,
                NguoiTao: MA_ND,
                details: details
            };

            try {
                const resp = await fetch("/hoadon", { // gọi nội bộ local
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": CSRF_TOKEN
                    },
                    body: JSON.stringify(hoaDonData)
                });

                const result = await resp.json();
                if (result.success) {
                    alert("🎉 Thanh toán & đặt vé thành công!");
                    sessionStorage.clear();
                    window.location.href = "/";
                } else {
                    alert("⚠️ Thanh toán thành công nhưng lưu vé thất bại.");
                }
            } catch (err) {
                console.error(err);
                alert("⚠️ Thanh toán thành công nhưng không thể lưu hóa đơn.");
            }
        }
    });
});
</script>
@endsection
