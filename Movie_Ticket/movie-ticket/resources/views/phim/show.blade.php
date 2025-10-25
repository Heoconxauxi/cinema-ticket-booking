{@extends('layouts.app')
@section('title', $phim['TenPhim'])

@section('content')
<div class="container-fluid bg-dark text-white p-0">

    {{-- 🎞️ Banner phim --}}
    @if(!empty($phim['Banner']))
    <div class="banner position-relative">
        <img src="{{ $phim['Banner'] }}" class="w-100" style="height:400px;object-fit:cover;">
        <div class="position-absolute top-50 start-50 translate-middle text-center bg-dark bg-opacity-50 p-3 rounded">
            <h2 class="fw-bold">{{ $phim['TenPhim'] }}</h2>
        </div>
    </div>
    @endif

    <div class="container mt-5">
        <div class="row">
            {{-- Poster phim --}}
            <div class="col-md-4">
                {{-- Khoảng trống tránh navbar che nội dung --}}
                <div style="height:10px"></div>
                <img src="{{ $phim['Anh'] }}" class="img-fluid rounded shadow" alt="{{ $phim['TenPhim'] }}">
            </div>

            {{-- Thông tin phim --}}
            <div class="col-md-8 text-start">
                <h2 class="text-warning">{{ $phim['TenPhim'] }}</h2>
                <p><strong> Đạo diễn:</strong> {{ $phim['DaoDien'] }}</p>
                <p><strong> Diễn viên:</strong> {{ $phim['DienVien'] }}</p>
                <div class="d-flex flex-wrap gap-3">
                    <p><strong> Quốc gia:</strong> {{ $phim['QuocGia'] }}</p>
                    <?php
                    // Giả sử $phim['TenPhim'] = "Deadpool & Wolverine"
                    $tentimkiem = urlencode("trailer " . $phim['TenPhim']); // Deadpool+%26+Wolverine
                    ?>

                    <div class="d-inline-flex align-items-center ms-2">
                        <button class="btn btn-danger btn-sm" onclick="playTrailer('{{ $tentimkiem }}')">
                            🎬 Play Trailer
                        </button>
                    </div>

                    <div id="videoPopup" class="popup" style="display:none;">
                        <div class="popup-content">
                            <span class="close-btn" onclick="closePopup()">&times;</span>
                            <iframe id="trailerFrame" width="100%" height="600" frameborder="0" allowfullscreen></iframe>
                        </div>
                    </div>

                    <style>
                        .popup {
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                            background: rgba(0, 0, 0, 0.9);
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            z-index: 10000;
                        }

                        .popup-content {
                            position: relative;
                            width: 80%;
                            max-width: 1000px;
                            background: #000;
                            border-radius: 10px;
                            overflow: hidden;
                        }

                        .close-btn {
                            position: absolute;
                            top: 5px;
                            right: 15px;
                            font-size: 32px;
                            color: white;
                            cursor: pointer;
                        }
                    </style>

                    <script>
                        async function playTrailer(query) {
                            const apiKey = "AIzaSyCDOpMGc4dTgF9NYsXSm1w4GNT2XvJjJkc"; // 🔑 Thay bằng API Key của bạn
                            const url = `https://www.googleapis.com/youtube/v3/search?part=snippet&q=${query}&key=${apiKey}&type=video&maxResults=1`;

                            try {
                                const res = await fetch(url);
                                const data = await res.json();

                                if (data.items && data.items.length > 0) {
                                    const videoId = data.items[0].id.videoId;
                                    const iframe = document.getElementById('trailerFrame');
                                    iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&modestbranding=1`;
                                    document.getElementById('videoPopup').style.display = 'flex';
                                } else {
                                    alert("Không tìm thấy trailer!");
                                }
                            } catch (error) {
                                alert("Lỗi khi tải trailer: " + error);
                            }
                        }

                        // Hide popup and stop the video
                        function closePopup() {
                            const iframe = document.getElementById('trailerFrame');
                            if (iframe) {
                                // stop and clear the iframe to stop audio/video playback
                                iframe.src = '';
                            }
                            const popup = document.getElementById('videoPopup');
                            if (popup) {
                                popup.style.display = 'none';
                            }
                        }

                        // Allow clicking outside the content to close the popup
                        document.addEventListener('DOMContentLoaded', function() {
                            const popup = document.getElementById('videoPopup');
                            if (popup) {
                                popup.addEventListener('click', function(e) {
                                    if (e.target === popup) {
                                        closePopup();
                                    }
                                });
                            }
                        });
                    </script>
                </div>
                <div class="d-flex flex-wrap gap-3">
                    <p class="mb-0"><strong>📅 Năm:</strong> {{ $phim['NamPhatHanh'] }}</p>
                    <p class="mb-0"><strong>🕒 Thời lượng:</strong> {{ $phim['ThoiLuong'] }} phút</p>
                </div>
                <div class="mt-3">
                    <strong>🎭 Thể loại:</strong>
                    @foreach($phim['theloais'] as $tl)
                    <span class="badge bg-secondary">{{ $tl['TenTheLoai'] }}</span>
                    @endforeach
                </div>

                <hr>
                <h5 class="text-info"> Nội dung</h5>
                <p>{{ $phim['MoTa'] }}</p>

                {{-- 🎟️ Lịch chiếu --}}
                <div class="mt-5">
                    <h4 class="text-primary mb-3">🗓️ Lịch chiếu</h4>

                    {{-- Nút chọn ngày --}}
                    <div id="date-buttons" class="d-flex flex-wrap gap-2 justify-content-center mb-3"></div>

                    {{-- Khu vực hiển thị suất chiếu theo ngày --}}
                    <div id="suat-container" class="text-center"></div>
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded", () => {
                        const dateButtonsContainer = document.getElementById('date-buttons');
                        const suatContainer = document.getElementById('suat-container');
                        const allShowtimes = @json($suatChieuPhim);

                        // 🔥 THÊM 2 DÒNG: Lấy giờ hiện tại và định dạng ngày hôm nay
                        const now = new Date();
                        const todayStr = now.toISOString().split('T')[0]; // Lấy chuỗi "YYYY-MM-DD" của hôm nay

                        // 📅 Tạo 5 nút ngày kế tiếp
                        const today = new Date();
                        for (let i = 0; i < 5; i++) {
                            const date = new Date(today);
                            date.setDate(today.getDate() + i);
                            const yyyy = date.getFullYear();
                            const mm = String(date.getMonth() + 1).padStart(2, '0');
                            const dd = String(date.getDate()).padStart(2, '0');
                            const formatted = `${yyyy}-${mm}-${dd}`;

                            const btn = document.createElement('button');
                            btn.className = 'btn btn-outline-warning';
                            btn.textContent = (i === 0 ? 'Hôm nay' : date.toLocaleDateString('vi-VN', {
                                weekday: 'long',
                                day: '2-digit',
                                month: '2-digit'
                            }));
                            btn.dataset.date = formatted;
                            btn.onclick = () => renderShowtimes(formatted, btn);
                            dateButtonsContainer.appendChild(btn);
                        }

                        // 🔥 HÀM RENDER ĐÃ SỬA LOGIC LỌC
                        function renderShowtimes(date, btn) {
                            document.querySelectorAll('#date-buttons button').forEach(b => b.classList.remove('active', 'btn-warning', 'text-dark'));
                            btn.classList.add('active', 'btn-warning', 'text-dark');

                            // Kiểm tra xem ngày được chọn có phải là hôm nay không
                            const isToday = (date === todayStr);

                            const filtered = allShowtimes.filter(sc => {
                                // 1. Lọc theo ngày (giống như cũ)
                                const isOnSelectedDate = sc.GioChieu.startsWith(date);
                                if (!isOnSelectedDate) {
                                    return false;
                                }

                                // 2. Nếu là "Hôm nay", thì lọc thêm theo giờ
                                if (isToday) {
                                    const showTime = new Date(sc.GioChieu);
                                    // Chỉ trả về true (hiển thị) nếu giờ chiếu >= giờ hiện tại
                                    return showTime >= now;
                                }

                                // 3. Nếu là ngày khác (không phải hôm nay), chỉ cần lọc theo ngày
                                return true;
                            });

                            if (filtered.length === 0) {
                                suatContainer.innerHTML = `<p class='text-light mt-3'>Không có suất chiếu trong ngày này.</p>`;
                                return;
                            }

                            let html = `<div class="d-flex flex-wrap justify-content-center gap-2">`;
                            filtered.forEach(sc => {
                               // 🔥 SỬA LỖI MÚI GIỜ (TIMEZONE)
                                // 1. Lấy chuỗi từ API (vd: "2025-10-25T21:00:00.000000Z")
                                // 2. Cắt bỏ phần ".000000Z" và thay 'T' bằng ' '
                                const localTimeStr = sc.GioChieu.substring(0, 19).replace('T', ' '); // -> "2025-10-25 21:00:00"

                                // 3. new Date() bây giờ sẽ hiểu đây là GIỜ ĐỊA PHƯƠNG, không phải UTC
                                const time = new Date(localTimeStr).toLocaleTimeString('vi-VN', {
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
                                // Link (button) của bạn
                                html += `
                    <button 
                        class="btn btn-outline-light m-1"
                        onclick="chonSuatChieu(${sc.MaPhim}, ${sc.MaSuatChieu}, ${sc.MaPhong})">
                        ${time} (${sc.phong?.TenPhong || 'Rạp chưa rõ'})
                    </button>
                `;
                            });
                            html += `</div>`;
                            suatContainer.innerHTML = html;
                        }

                        // Hiển thị mặc định hôm nay
                        const firstBtn = dateButtonsContainer.querySelector('button');
                        if (firstBtn) firstBtn.click();
                    });
                </script>

                <script>
                    function chonSuatChieu(maPhim, maSuat, maPhong) {
                        const isLoggedIn = @json(session('NDloggedIn') ?? false);

                        if (!isLoggedIn) {
                            if (confirm("⚠️ Bạn cần đăng nhập để đặt vé.\nNhấn OK để đến trang đăng nhập.")) {
                                window.location.href = "/login";
                            }
                            return;
                        }

                        window.location.href = `/phim/${maPhim}/dat-ve/${maSuat}/${maPhong}`;
                    }
                </script>




            </div>


        </div>


    </div>
</div>
@endsection