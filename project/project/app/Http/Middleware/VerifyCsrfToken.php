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
        '*save-token*',
        '*sort-payment-methods*',
        '*admin/upload/ck/image*',
        'mpesa/webhook', // Safaricom posts payment callbacks here (verified against their API before crediting)
    ];
}
