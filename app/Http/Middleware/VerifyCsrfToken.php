<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [

        '/webhook/razorpay',
        '/webhook/paystack',
        '/webhook/paypal',
        '/webhook/stripe',

        '/firebase_messaging_settings',

        // Exclude Telegram WebApp Login from CSRF check (uses Telegram InitData signature instead)
        '/api/webapp/login',
        '/webapp/submit-listing',
        '/webapp/update-listing/*',
        '/webapp/listings/*',
        '/webapp/listings/*/toggle',
    ];
}