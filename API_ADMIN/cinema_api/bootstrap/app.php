<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Đây là nơi bạn đăng ký routeMiddleware
        $middleware->alias([
            'auth.admin' => \App\Http\Middleware\CheckAdminRole::class,
            // Thêm các alias khác của bạn ở đây nếu cần
            // 'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
            // 'auth' => \App\Http\Middleware\Authenticate::class,
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            // Nếu request đi đến đường dẫn admin/*
            if ($request->is('admin/*')) {
                // Chuyển hướng về route login của admin
                return route('admin.login.form');
            }

            // Mặc định, chuyển hướng về route 'login' (cho trang user nếu có)
            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
