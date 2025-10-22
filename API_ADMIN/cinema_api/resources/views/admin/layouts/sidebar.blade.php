<aside id="sidenav-main" class="sidenav navbar navbar-vertical navbar-expand-xs border-0 my-3 ms-3 rounded-3 fixed-start">
    <div class="sidenav-header text-center py-3">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand m-0" href="{{ route('admin.home') }}">
                <img src="{{ asset('assets/icon/logo-100x100.png') }}" class="w-25 bg-white rounded-circle p-1" alt="Logo">
                <h5 class="text-white mt-2 mb-0 fw-bold">LalaCinema</h5>
            </a>
        </div>  
    </div>

    <hr class="horizontal light mt-0 mb-3 opacity-25">

    <ul class="navbar-nav px-2">
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin') ? 'active' : '' }}" href="{{ route('admin.home') }}">
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
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/theloai*') ? 'active' : '' }}" href="{{ route('admin.theloai.index') }}">
                <i class="fas fa-film me-2"></i> Danh sách thể loại
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/phim*') ? 'active' : '' }}" href="{{ route('admin.phim.index') }}">
                <i class="fas fa-film me-2"></i> Danh sách phim
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/suatchieu*') ? 'active' : '' }}" href="{{ route('admin.suatchieu.index') }}">
                <i class="fas fa-film me-2"></i> Danh sách suất chiếu
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/chude*') ? 'active' : '' }}" href="{{ route('admin.chude.index') }}">
                <i class="fas fa-film me-2"></i> Danh sách chủ đề
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->is('admin/baiviet*') ? 'active' : '' }}" href="{{ route('admin.baiviet.index') }}">
                <i class="fas fa-film me-2"></i> Danh sách bài viết
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
