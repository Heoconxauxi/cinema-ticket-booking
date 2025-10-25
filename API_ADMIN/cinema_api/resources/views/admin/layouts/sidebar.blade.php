<aside id="sidenav-main" class="sidenav navbar navbar-vertical navbar-expand-xs border-0 my-3 ms-3 rounded-3 fixed-start">
    <div class="sidenav-header text-center py-3">
        <div class="container d-flex align-items-center justify-content-center">
            <a class="navbar-brand m-0" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('assets/icon/logo.png') }}" class="w-25 bg-white rounded-circle p-1" alt="Logo">
                <h5 class="text-white mt-2 mb-0 fw-bold">WAPP.C-11</h5>
            </a>
        </div>
    </div>

    <hr class="horizontal light mt-0 mb-3 opacity-25">

    <ul class="navbar-nav px-2" style="max-height: 80vh; overflow-y: auto;">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/phong*') ? 'active' : '' }}" href="{{ route('admin.phong.index') }}">
                <i class="fas fa-door-open me-2"></i> Danh sách phòng
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/ghe*') ? 'active' : '' }}" href="{{ route('admin.ghe.index') }}">
                <i class="fas fa-chair me-2"></i> Danh ghế
            </a>
        </li>

        @php
            $phimGroupActive = request()->is('admin/theloai*') || request()->is('admin/phim*') || request()->is('admin/suatchieu*');
        @endphp
        <li class="nav-item">
            <a class="nav-link {{ $phimGroupActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#collapsePhim" role="button" aria-expanded="{{ $phimGroupActive ? 'true' : 'false' }}" aria-controls="collapsePhim">
                <i class="fas fa-film me-2"></i> Quản lý Phim
            </a>
            <div class="collapse {{ $phimGroupActive ? 'show' : '' }}" id="collapsePhim">
                <ul class="nav flex-column ms-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/theloai*') ? 'active' : '' }}" href="{{ route('admin.theloai.index') }}">
                            Danh sách thể loại
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/phim*') ? 'active' : '' }}" href="{{ route('admin.phim.index') }}">
                            Danh sách phim
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/suatchieu*') ? 'active' : '' }}" href="{{ route('admin.suatchieu.index') }}">
                            Danh sách suất chiếu
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        @php
            $contentGroupActive = request()->is('admin/chude*') || request()->is('admin/baiviet*');
        @endphp
        <li class="nav-item">
            <a class="nav-link {{ $contentGroupActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#collapseContent" role="button" aria-expanded="{{ $contentGroupActive ? 'true' : 'false' }}" aria-controls="collapseContent">
                <i class="fas fa-newspaper me-2"></i> Quản lý Nội dung
            </a>
            <div class="collapse {{ $contentGroupActive ? 'show' : '' }}" id="collapseContent">
                <ul class="nav flex-column ms-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/chude*') ? 'active' : '' }}" href="{{ route('admin.chude.index') }}">
                            Danh sách chủ đề
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/baiviet*') ? 'active' : '' }}" href="{{ route('admin.baiviet.index') }}">
                            Danh sách bài viết
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        @php
            $uiGroupActive = request()->is('admin/slider*') || request()->is('admin/menu*');
        @endphp
        <li class="nav-item">
            <a class="nav-link {{ $uiGroupActive ? 'active' : '' }}" data-bs-toggle="collapse" href="#collapseUI" role="button" aria-expanded="{{ $uiGroupActive ? 'true' : 'false' }}" aria-controls="collapseUI">
                <i class="fas fa-desktop me-2"></i> Quản lý Giao diện
            </a>
            <div class="collapse {{ $uiGroupActive ? 'show' : '' }}" id="collapseUI">
                <ul class="nav flex-column ms-4">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/slider*') ? 'active' : '' }}" href="{{ route('admin.slider.index') }}">
                            Quản lý Slider
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('admin/menu*') ? 'active' : '' }}" href="{{ route('admin.menu.index') }}">
                            Quản lý Menu
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/thamso*') ? 'active' : '' }}" href="{{ route('admin.thamso.index') }}">
                <i class="fas fa-cog me-2"></i> Danh sách tham số
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/hoadon*') ? 'active' : '' }}" href="{{ route('admin.hoadon.index') }}">
                <i class="fas fa-file-invoice-dollar me-2"></i> Danh sách hóa đơn
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/nguoidung*') ? 'active' : '' }}" href="{{ route('admin.nguoidung.index') }}">
                <i class="fas fa-users me-2"></i> Quản lý người dùng
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/taikhoan*') ? 'active' : '' }}" href="{{ route('admin.taikhoan.index') }}">
                <i class="fas fa-user-circle me-2"></i> Quản lý tài khoản
            </a>
        </li>

        <li class="mt-4">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn bg-gradient-primary w-100 text-white fw-bold">
                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                </button>
            </form>
        </li>
    </ul>
</aside>