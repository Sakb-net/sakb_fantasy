<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/search',
        '*/search',
        '*/search/*',
        '/login',
        '*/login',
        '*/login/*',
        '/register',
        '*/register',
        '*/register/*',
        '/logout',
        '*/logout',
        '*/logout/*',
        'api/*',
        'localhost/*',
        'http://localhost/*',
        'https://localhost/*',
    ];
}
