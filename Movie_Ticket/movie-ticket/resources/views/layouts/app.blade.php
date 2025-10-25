<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '🎥 WAPP.C-11 | Movie Ticket')</title>

    {{-- Bootstrap & Fonts --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- Custom Style --}}
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #2f0b5fff;
            color: #eaeaea;
            /* margin: 0; */
            padding: 0;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .navbar-dark {
            background: linear-gradient(90deg, #0f172a 0%, #1e293b 100%);
        }

        .card {
            background-color: #18181b;
            border: none;
            border-radius: 1rem;
            transition: transform .2s, box-shadow .2s;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.5);
        }

        footer {
            background: #111;
            color: #aaa;
            font-size: 0.9rem;
            text-align: center;
            padding: 1.2rem 0;
            /* margin-top: 3rem; */
        }

        .carousel-item img {
            height: 500px;
            object-fit: cover;
            border-radius: 1rem;
        }

        .auth-wrapper {
            min-height: calc(100vh - 180px);
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    {{-- HEADER --}}
    @include('partials.header')

    {{-- FLASH MESSAGE --}}
    @if (session('success'))
    <div class="alert alert-success text-center m-0 rounded-0">{{ session('success') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger text-center m-0 rounded-0">{{ session('error') }}</div>
    @endif

    {{-- MAIN CONTENT --}}
    <main class="container my-4 py-3">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    @include('partials.footer')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>