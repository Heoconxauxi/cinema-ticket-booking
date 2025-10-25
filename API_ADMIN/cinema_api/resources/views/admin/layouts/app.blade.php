<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <title>@yield('title') - Admin Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @include('admin.layouts.links')
    
    @stack('scripts')

    @stack('styles')
    <style>
        /* --- Layout chính & Body --- */
        body {
            background: linear-gradient(135deg, #ffffffff, #f3f4f6);
            font-family: 'Open Sans', sans-serif;
        }

        /* Đẩy nội dung chính sang phải để chừa chỗ cho sidebar */
        .g-sidenav-show .main-content {
            margin-left: 280px;
            margin-right: 0;
            transition: all 0.3s ease;
        }

        /* Giúp footer luôn nằm ở dưới (Sticky Footer) */
        .container-fluid {
            /* (Viewport - Navbar - Footer) */
            min-height: calc(100vh - 55px - 60px); 
            padding-bottom: 60px; /* Chừa không gian cho footer */
        }

        /* --- Sidebar --- */
        #sidenav-main {
            /* Vị trí & Kích thước */
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            transform: translateX(0);
            transition: all 0.3s ease;
            z-index: 1050;

            /* Styling */
            background: linear-gradient(180deg, #1e3a8a, #312e81);
            color: #fff;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }

        #sidenav-main .nav-link {
            color: #cbd5e1;
            border-radius: .75rem;
            transition: 0.3s;
            margin: 4px 8px;
            font-weight: 500;
        }

        #sidenav-main .nav-link:hover,
        #sidenav-main .nav-link.active {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: #fff;
            transform: translateX(5px);
        }

        /* --- Các thành phần (Navbar, Card, Footer) --- */
        .navbar-main {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-radius: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 16px;
        }

        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.2s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .footer {
            width: 100%;
            height: 60px;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(8px);
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            font-size: 0.9rem;
            text-align: center;
            padding-top: 20px;
        }

        /* --- Responsive (trên di động) --- */
        @media (max-width: 991px) {
            #sidenav-main {
                width: 220px;
                transform: translateX(-100%);
                transition: transform .3s ease;
            }
            
            /* Class này (thường được JS thêm vào) để hiện sidebar */
            #sidenav-main.show {
                transform: translateX(0);
            }
            
            /* Trả nội dung chính về 100% chiều rộng */
            .g-sidenav-show .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body class="g-sidenav-show bg-gray-100">

    @include('admin.layouts.sidebar')

    <main class="main-content position-relative h-100">
        @include('admin.layouts.navbar')

        <div class="container-fluid py-4">
            @yield('content')
        </div>

        @include('admin.layouts.footer')
    </main>

    @include('admin.layouts.scripts')
    @stack('scripts')
</body>

</html>