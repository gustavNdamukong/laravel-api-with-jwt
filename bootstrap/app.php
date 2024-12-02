<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Add your custom VerifyCsrfToken middleware
        /////$middleware->append(\Illuminate\Session\Middleware\StartSession::class);
        /////$middleware->append(App\Middleware\VerifyCsrfToken::class);
        /*$middleware->validateCsrfTokens(
            except: [
                'api/*',
                'http://localhost:8888/laravel-api-with-jwt/public/api/v1/customers',
                'http://localhost:8888/laravel-api-with-jwt/public/api/v1/invoices',
            ]
        );
        */

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
