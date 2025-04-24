<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
class CustomCsrfMiddleware extends Middleware
{
    /**
     * Las URIs que deben excluirse de la verificación CSRF.
     *
     * @var array
     */
    protected $except = [
        //'api/auth/register',
        //'api/auth/login'
    ];
}
