<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // Menangani aplikasi saat maintenance
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,

        // Menangani ukuran request terlalu besar
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,

        // Trim input string
        \App\Http\Middleware\TrimStrings::class,

        // Ubah empty string jadi null
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,

        // Menangani CORS
        \Illuminate\Http\Middleware\HandleCors::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // limit request untuk API
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * Bisa dipanggil langsung di routes atau controller.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth'       => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest'      => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'verified'   => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];
}
