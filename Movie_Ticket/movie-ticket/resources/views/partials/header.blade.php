<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
  <div class="container">
    {{-- Logo --}}
    <a class="navbar-brand fw-bold" href="{{ url('/') }}">
      🎥 WAPP.C-11
      <small class="text-secondary">-- Web Application Cinema Group 11 --</small>
    </a>

    {{-- Nút toggle cho mobile --}}
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="mainNav">
      <ul class="navbar-nav align-items-center">
        {{-- MENU PHIM --}}
        <li class="nav-item dropdown mx-2">
          <a class="nav-link dropdown-toggle" href="#" id="phimDropdown" role="button" data-bs-toggle="dropdown">
            🎬 Phim
          </a>
          <ul class="dropdown-menu" aria-labelledby="phimDropdown">
            <li><a class="dropdown-item" href="{{ route('suat-chieu') }}">🎟️ Tìm theo suất chiếu</a></li>
          </ul>
        </li>

        {{-- Góc điện ảnh & Liên hệ --}}
        <li class="nav-item mx-2"><a class="nav-link" href="{{ url('/blog') }}">🎞️ Góc điện ảnh</a></li>
        <li class="nav-item mx-2"><a class="nav-link" href="{{ route('contact') }}">📞 Liên hệ</a></li>

        {{-- TRẠNG THÁI NGƯỜI DÙNG --}}
        @if (session('NDloggedIn'))
        <li class="nav-item dropdown ms-3">
          <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userMenu" role="button" data-bs-toggle="dropdown">
            <span>{{ session('TenND') ?? 'Người dùng' }}</span>
          </a>

          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="{{ url('/profile') }}">👤 Hồ sơ cá nhân</a></li>
            <li><a class="dropdown-item" href="{{ url('/tickets') }}">🎫 Vé đã đặt</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item text-danger">🚪 Đăng xuất</button>
              </form>
            </li>
          </ul>
        </li>
        @else
        {{-- Khi chưa đăng nhập --}}
        <li class="nav-item ms-3">
          <a class="btn btn-outline-light rounded-pill px-3 py-1" href="{{ route('login') }}">
            🔑 Đăng nhập / Đăng ký
          </a>
        </li>
        @endif
      </ul>
    </div>
  </div>
</nav>