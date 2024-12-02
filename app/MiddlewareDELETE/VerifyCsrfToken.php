<?php

namespace App\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

use function Illuminate\Log\log;

class VerifyCsrfToken extends Middleware
{
    /*protected $except = [
        'api/*',
        'v1/*',
    ];
    */

    /////protected $except = ['*'];
    protected $except = [
        /////'http://localhost:8888/laravel-api-with-jwt/public/v1/customers'
    ];
}


