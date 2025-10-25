<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Đăng nhập</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Ảnh nền toàn màn hình */
        .oblique-image {
            background-image: url("{{ asset('assets/imgs/curved-images/curved6.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            filter: brightness(0.7); /* giảm sáng nền để chữ dễ đọc */
        }

        /* Khung form login ở giữa */
        .login-wrapper {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Card đăng nhập */
        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
            width: 380px;
            max-width: 90%;
            padding: 35px 40px;
            animation: fadeIn 0.7s ease;
        }

        /* Tiêu đề và nút */
        .login-card h1 {
            text-align: center;
            margin-bottom: 25px;
            font-weight: 700;
            color: #0d6efd;
        }

        .btn-login {
            width: 100%;
            font-size: 17px;
            font-weight: 600;
            border-radius: 10px;
        }

        /* CSS cho icon con mắt bên trong input-group */
        #password-addon {
            cursor: pointer;
            padding: 10px; /* Thêm đệm để dễ click hơn */
            border: 1px solid #ced4da; /* Thêm viền giống input */
            border-left: none; /* Bỏ viền trái */
            background: #fff; /* Nền trắng */
            border-top-right-radius: 0.375rem; /* Bo góc phải */
            border-bottom-right-radius: 0.375rem;
        }
        
        /* Đảm bảo input không có viền bo bên phải khi đi chung */
        .input-group > .form-control:not(:last-child) {
             border-top-right-radius: 0;
             border-bottom-right-radius: 0;
        }

        /* Hiệu ứng vào */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body> <div id="toast"></div>

<main>
    <div class="oblique-image"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <h1>Đăng nhập</h1>

            @if ($errors->any())
                <div class="alert alert-danger text-white">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form role="form" action="{{ route('admin.login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="TenDangNhap" class="fs-5">Tên đăng nhập</label>
                    <input type="text" id="TenDangNhap" class="form-control" name="TenDangNhap"
                           placeholder="Tên đăng nhập" value="{{ old('TenDangNhap') }}">
                    @error('TenDangNhap')
                        <div class="text-danger mt-1 text-xs">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="fs-5">Mật khẩu</label>
                    <div class="input-group">
                        <input type="password" id="password" name="MatKhau" class="form-control"
                               placeholder="Mật khẩu">
                        <span class="icon" id="password-addon">
                            <i class="fas fa-eye-slash" id="togglePassword"></i>
                        </span>
                    </div>
                    @error('MatKhau')
                        <div class="text-danger mt-1 text-xs">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-login mt-3">Đăng nhập</button>
            </form>
        </div>
    </div>
</main>

<div class="mt-5">
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('password');
        const icon = this; // 'this' là icon <i>

        // Thay đổi loại input và icon
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>