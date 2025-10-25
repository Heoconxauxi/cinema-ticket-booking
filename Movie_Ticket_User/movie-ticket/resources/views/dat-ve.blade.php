@extends('layouts.app')
@section('title', 'Đặt vé - ' . ($phim['TenPhim'] ?? ''))

@section('content')
<div class="container mt-5 text-white">

    {{-- 🔹 Thông tin phim & suất chiếu --}}
    <div class="text-center mb-4">
        <h2 class="text-warning">{{ $phim['TenPhim'] }}</h2>
        @if(!empty($suat) && !empty($suat['GioChieu']))
        <p><strong>⏰ Giờ chiếu:</strong> {{ date('H:i d/m/Y', strtotime($suat['GioChieu'])) }}</p>
        @else
        <p class="text-danger">⏰ Thông tin giờ chiếu không khả dụng.</p>
        @endif

        <p><strong>🏢 Phòng chiếu:</strong> {{ $suat['phong']['TenPhong'] ?? 'Rạp chưa rõ' }}</p>
        <img src="{{ $phim['Anh'] }}" class="img-fluid rounded shadow mt-3" style="max-height:300px;">
    </div>

    <hr class="border-light">

    {{-- 🏗️ Sơ đồ ghế chi tiết --}}
    <div class="text-center mb-4">
        <div class="mb-3">
            <h5 class="text-warning">🎬 MÀN HÌNH</h5>
            <div style="height: 6px; width: 80%; margin: auto; background: linear-gradient(to right, #999, #fff, #999); border-radius: 5px;"></div>
        </div>

        {{-- Dãy ghế A–D (ghế đơn) --}}
        @php
        $rows = ['A','B','C','D'];
        @endphp
        <div class="d-flex flex-column align-items-center gap-3 mb-5">
            @foreach($rows as $row)
            <div class="d-flex align-items-center gap-2">
                <span class="fw-bold">{{ $row }}</span>
                @foreach($ghes as $ghe)
                @if(Str::startsWith($ghe['TenGhe'], $row))

                {{-- 🔥 LOGIC KIỂM TRA GHẾ --}}
                @php
                $isBooked = in_array($ghe['MaGhe'], $gheDaBanIds);
                $isAvailable = $ghe['TrangThai'] == 1;
                $class = '';

                if ($isBooked) {
                $class = 'btn-secondary disabled'; // Đã bán (Xám)
                } elseif (!$isAvailable) {
                $class = 'btn-dark disabled'; // Ghế hỏng (Tối)
                } else {
                $class = 'btn-outline-success'; // Trống
                }
                @endphp

                <button class="seat btn m-1 {{ $class }}"
                    data-id="{{ $ghe['MaGhe'] }}"
                    data-name="{{ $ghe['TenGhe'] }}"
                    data-gia="{{ $ghe['GiaGhe'] }}"
                    {{-- Vô hiệu hóa nút nếu đã bán hoặc ghế hỏng --}}
                    {{ $isBooked || !$isAvailable ? 'disabled' : '' }}>
                    {{ substr($ghe['TenGhe'], 1) }}
                </button>
                @endif
                @endforeach
                <span class="fw-bold">{{ $row }}</span>
            </div>
            @endforeach
        </div>

        {{-- Dãy E (ghế đôi - couple) --}}
        <div class="text-center mt-4">
            <h6 class="text-pink fw-bold">💞 Dãy ghế Couple (E)</h6>
            <div class="d-flex justify-content-center flex-wrap gap-3">
                @foreach($ghes as $ghe)
                @if(Str::startsWith($ghe['TenGhe'], 'E'))

                {{-- 🔥 LOGIC KIỂM TRA GHẾ (TƯƠNG TỰ) --}}
                @php
                $isBooked = in_array($ghe['MaGhe'], $gheDaBanIds);
                $isAvailable = $ghe['TrangThai'] == 1;
                $class = '';

                if ($isBooked) {
                $class = 'btn-secondary disabled'; // Đã bán
                } elseif (!$isAvailable) {
                $class = 'btn-dark disabled'; // Ghế hỏng
                } else {
                $class = 'btn-outline-danger'; // Trống (cho ghế đôi)
                }
                @endphp

                <button class="seat btn m-1 px-4 {{ $class }}"
                    style="width:100px;"
                    data-id="{{ $ghe['MaGhe'] }}"
                    data-name="{{ $ghe['TenGhe'] }}"
                    data-gia="{{ $ghe['GiaGhe'] }}"
                    {{ $isBooked || !$isAvailable ? 'disabled' : '' }}>
                    {{ $ghe['TenGhe'] }}
                </button>
                @endif
                @endforeach
            </div>
        </div>
              {{-- Lối đi và Exit --}}
        <div class="text-center mt-5 text-muted">
            <p>🚪 Exit | Lối đi giữa | 🚪 Exit</p>
        </div>
    </div>




    {{-- 🔹 Tổng tiền & nút đặt --}}
    <div class="text-center mt-4">
        <h5>Tổng tiền: <span id="tongTien" class="text-info fw-bold">0</span> đ</h5>
        <button id="btnDatVe" class="btn btn-warning mt-3" disabled>👉 Tiếp tục thanh toán</button>
    </div>

</div>

<script>
    let selectedSeats = new Set();
    let total = 0;

    // Mã định danh tạm cho người dùng (duy nhất trên trình duyệt này)
    let clientId = localStorage.getItem('clientId');
    if (!clientId) {
        clientId = 'client_' + Math.random().toString(36).substring(2, 10);
        localStorage.setItem('clientId', clientId);
    }

    // 🧹 Xóa tất cả lock ghế cũ (khi load lại trang)
    for (let key in localStorage) {
        if (key.startsWith('lock_')) {
            localStorage.removeItem(key);
        }
    }

    document.querySelectorAll('.seat').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const gia = parseInt(btn.dataset.gia);
            const lockInfo = localStorage.getItem(`lock_${id}`);

            // 🔒 Nếu ghế bị người khác chọn
            if (lockInfo && lockInfo !== clientId) {
                alert(`⚠️ Ghế ${btn.dataset.name} đang được người khác chọn!`);
                return;
            }

            // ✅ Nếu ghế do chính bạn chọn
            if (selectedSeats.has(id)) {
                // Bỏ chọn
                selectedSeats.delete(id);
                total -= gia;
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-outline-success');
                localStorage.removeItem(`lock_${id}`);
            } else {
                // Chọn
                selectedSeats.add(id);
                total += gia;
                btn.classList.remove('btn-outline-success');
                btn.classList.add('btn-danger');
                localStorage.setItem(`lock_${id}`, clientId);
            }

            // Cập nhật tổng tiền
            document.getElementById('tongTien').textContent = total.toLocaleString('vi-VN');
            document.getElementById('btnDatVe').disabled = selectedSeats.size === 0;
        });
    });

    document.getElementById('btnDatVe').addEventListener('click', () => {
        const gheIds = Array.from(selectedSeats);
        if (gheIds.length === 0) return;

        sessionStorage.setItem('selectedSeats', JSON.stringify(gheIds));
        sessionStorage.setItem('tongTien', total);
        sessionStorage.setItem('clientId', clientId);

       
        sessionStorage.setItem('maSuatChieu', {{ $suat['MaSuatChieu'] }});
        sessionStorage.setItem('phim', JSON.stringify(@json($phim)));
        sessionStorage.setItem('suat', JSON.stringify(@json($suat)));

        window.location.href = `/thanhtoan`;
    });
</script>

<style>
    .seat {
        width: 60px;
        height: 60px;
        font-weight: bold;
        border-radius: 10px;
    }

    .btn-danger {
        background-color: red !important;
        color: white;
    }

    .seat {
        width: 60px;
        height: 60px;
        font-weight: bold;
        border-radius: 10px;
        transition: 0.2s ease;
    }

    .btn-danger {
        background-color: red !important;
        color: white;
    }

    .btn-outline-danger {
        border-color: #ff4d6d !important;
        color: #ff4d6d !important;
    }

    .btn-outline-danger:hover {
        background-color: #ff4d6d !important;
        color: white !important;
    }

    .text-pink {
        color: #ff66a3;
    }
</style>
@endsection