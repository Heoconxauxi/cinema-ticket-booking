@extends('layouts.app')
@section('title', 'Suất Chiếu')

@section('content')
<div class="container mt-5 text-center">
    <div class="p-3 mb-4 border border-warning rounded bg-dark d-inline-block w-100">
        <h2 class="text-warning mb-0">🎟️ Danh Sách Suất Chiếu</h2>
    </div>

    {{-- Thanh chọn ngày --}}
    <div id="date-buttons" class="d-flex justify-content-center flex-wrap gap-2 mb-4"></div>

    {{-- Kết quả phim theo ngày --}}
    <div id="showtimes" class="row justify-content-center"></div>
</div>

<style>
    /* 🌟 Hiệu ứng nổi khung phim */
    .showtime-card {
        background: linear-gradient(145deg, rgba(30, 30, 30, 0.95), rgba(50, 50, 50, 0.85));
        border: 2px solid rgba(255, 193, 7, 0.4);
        border-radius: 15px;
        padding: 15px;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 0 10px rgba(255, 193, 7, 0.25);
    }

    .showtime-card:hover {
        transform: scale(1.02);
        box-shadow: 0 0 25px rgba(255, 193, 7, 0.6);
    }

    /* 🎞️ Ảnh phim có hiệu ứng phóng to nhẹ rồi thu nhỏ */
    .showtime-img {
        border-radius: 15px;
        border: 2px solid rgba(255, 255, 255, 0.15);
        width: 100%;
        transition: transform 0.6s ease-in-out;
        animation: breathe 6s ease-in-out infinite;
    }

    /* 🪶 Hiệu ứng nhẹ “phóng – thu” liên tục (như đang thở) */
    @keyframes breathe {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.04);
        }

        100% {
            transform: scale(1);
        }
    }

    /* 👆 Khi hover vào card, ảnh sẽ hơi lớn hơn chút */
    .showtime-card:hover .showtime-img {
        transform: scale(1.07);
        animation: none;
        /* dừng nhịp để ưu tiên hover */
    }

    /* Tiêu đề, bố cục */
    .showtime-header {
        border-bottom: 2px dashed rgba(255, 255, 255, 0.2);
        padding-bottom: 10px;
        margin-bottom: 15px;
    }
</style>


<script>
document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById('showtimes');

    // 🇻🇳 Lấy ngày HÔM NAY
    const now = new Date(); // Giờ hiện tại (ví dụ: 15:00 24/10/2025)
    const yyyy = now.getFullYear();
    const mm = String(now.getMonth() + 1).padStart(2, '0');
    const dd = String(now.getDate()).padStart(2, '0');
    const todayLocal = `${yyyy}-${mm}-${dd}`; // Chuỗi "2025-10-24"

    // Nút ngày
    const dateButtonsContainer = document.getElementById('date-buttons');
    const btn = document.createElement('button');
    btn.className = 'btn btn-success';
    btn.textContent = 'Hôm nay';
    btn.dataset.date = todayLocal;
    btn.onclick = () => loadShowtimes(todayLocal, btn);
    dateButtonsContainer.appendChild(btn);

    async function loadShowtimes(date, button) {
        document.querySelectorAll('#date-buttons button').forEach(b => b.classList.remove('active', 'btn-success'));
        button.classList.add('active', 'btn-success');

        container.innerHTML = `<div class='text-center text-secondary mt-3'>Đang tải dữ liệu...</div>`;

        try {
            const res = await fetch('http://127.0.0.1:8000/api/suatchieu');
            const data = await res.json();
            if (!data.success) throw new Error("Lỗi API");

            // 🔥 SỬA LỖI LỌC TIMEZONE VÀ ẨN SUẤT ĐÃ CHIẾU
            const filtered = data.data.filter(item => {
                // 1. Sửa lỗi Timezone (Quan trọng):
                // "2025-10-24T20:30:00.000000Z" -> "2025-10-24 20:30:00"
                const localTimeStr = item.GioChieu.substring(0, 19).replace('T', ' ');
                const showTime = new Date(localTimeStr); // Giờ chiếu (đã đúng)

                // 2. Lấy YYYY-MM-DD từ giờ chiếu
                const showY = showTime.getFullYear();
                const showM = String(showTime.getMonth() + 1).padStart(2, '0');
                const showD = String(showTime.getDate()).padStart(2, '0');
                const showDate = `${showY}-${showM}-${showD}`;

                // 3. Lọc: Phải đúng ngày CHỌN (date) VÀ (&&) Giờ chiếu phải >= Giờ hiện tại (now)
                // (Vì chỉ có nút 'Hôm nay' nên 'date' luôn là 'todayLocal')
                return (showDate === date) && (showTime >= now);
            });

            if (filtered.length === 0) {
                container.innerHTML = `<p class='text-light mt-3'>Không có suất chiếu nào sắp diễn ra hôm nay.</p>`;
                return;
            }

            // Gom theo phim
            const grouped = {};
            filtered.forEach(item => {
                const phim = item.phim;
                if (!grouped[phim.MaPhim]) grouped[phim.MaPhim] = { phim, suat: [] };
                
                // 🔥 SỬA LỖI TIMEZONE (LẦN 2) KHI HIỂN THỊ GIỜ
                // Lấy lại chuỗi local time đã xử lý ở trên
                const localTimeStr = item.GioChieu.substring(0, 19).replace('T', ' ');
                
                grouped[phim.MaPhim].suat.push({
                    MaSuatChieu: item.MaSuatChieu,
                    MaPhong: item.MaPhong,
                    // Dùng localTimeStr để tạo giờ, đảm bảo 20:30 là 20:30
                    gio: new Date(localTimeStr).toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' }), 
                    phong: item.phong?.TenPhong || 'Rạp chưa rõ'
                });
            });

            // Render
            container.innerHTML = "";
            Object.values(grouped).forEach(({ phim, suat }) => {
                // Sắp xếp lại suất chiếu theo giờ
                suat.sort((a, b) => a.gio.localeCompare(b.gio));

                let suatHTML = suat.map(s => `
                    <div class="col-lg-2 col-md-4 col-sm-6 text-center mb-2">
                        <button class="btn btn-outline-warning w-100 fw-bold"
                                onclick="chonSuatChieu(${phim.MaPhim}, ${s.MaSuatChieu}, ${s.MaPhong}, '${phim.TenPhim.replace(/'/g, "\\'")}')">
                            ${s.gio}
                        </button>
                        <div class="text-light small mt-1">🏢 ${s.phong}</div>
                    </div>
                `).join("");

                container.innerHTML += `
                <div class="showtime-card mb-5">
                    <div class="row align-items-center">
                        <div class="col-lg-5 col-md-5 col-sm-12">
                            <div class="position-relative">
                                <img src="${phim.Anh}" class="img-fluid showtime-img" alt="${phim.TenPhim}">
                                <a href="https://www.youtube.com/results?search_query=trailer+${encodeURIComponent(phim.TenPhim)}" 
                                   target="_blank" class="position-absolute top-0 start-0 mt-2 ms-2">
                                    <i class="fa fa-play-circle fa-2x text-danger"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-7 col-md-7 col-sm-12 text-start">
                            <div class="showtime-header">
                                <h3><a href="/phim/${phim.MaPhim}" class="text-warning text-decoration-none">${phim.TenPhim}</a></h3>
                                <ul class="list-inline text-secondary mb-0">
                                    <li class="list-inline-item"><i class="fa fa-tags"></i> ${phim.PhanLoai}</li>
                                    <li class="list-inline-item"><i class="fa fa-clock-o"></i> ${phim.ThoiLuong} phút</li>
                                </ul>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12 mb-2">
                                    <span class="badge bg-warning text-dark fs-6">2D Phụ đề</span>
                                </div>
                                ${suatHTML}
                            </div>
                        </div>
                    </div>
                </div>`;
            });
        } catch (err) {
            console.error(err);
            container.innerHTML = `<p class='text-danger'>Không thể tải dữ liệu suất chiếu.</p>`;
        }
    }

    // Tự load hôm nay
    btn.click();
});

// 🧷 Kiểm tra đăng nhập (Hàm này giữ nguyên, đã đúng)
function chonSuatChieu(maPhim, maSuat, maPhong, tenPhim) {
    const isLoggedIn = @json(session('NDloggedIn') ?? false);

    if (!isLoggedIn) {
        if (confirm("⚠️ Bạn cần đăng nhập để đặt vé.\nNhấn OK để đến trang đăng nhập.")) {
            window.location.href = "/login";
        }
        return;
    }

    // Lưu tạm thông tin tối thiểu cho trang kế (nếu cần)
    sessionStorage.setItem('maSuatChieu', String(maSuat));
    sessionStorage.setItem('maPhong', String(maPhong));
    sessionStorage.setItem('maPhim', String(maPhim));
    sessionStorage.setItem('tenPhim', tenPhim);

    // Chuyển đúng route đặt vé
    window.location.href = `/phim/${maPhim}/dat-ve/${maSuat}/${maPhong}`;
}
</script>

@endsection