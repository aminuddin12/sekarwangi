<?php

use App\Exceptions\Handler as CustomHandler;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\SecureApiGateway;
use App\Http\Middleware\VerifyApiAccount;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        // Mendaftarkan API V1 secara eksplisit dengan prefix 'api/v1'
        then: function () {
            Route::middleware(['api', SecureApiGateway::class, VerifyApiAccount::class])
                ->prefix('api/v1')
                ->group(base_path('routes/API/v1/api.php'));
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Mendaftarkan Middleware API Custom kita agar bisa dipanggil via alias jika perlu
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'api.account' => VerifyApiAccount::class, // Cek API Key
            'api.secure' => SecureApiGateway::class, // Cek IP/Device & Rate Limit
            'role.prefix' => \App\Http\Middleware\ValidateRolePrefix::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->reportable(function (Throwable $e) {
            CustomHandler::report($e);
        });

        $exceptions->renderable(function (Throwable $e, Request $request) {
            return CustomHandler::render($request, $e);
        });
    })->create();
