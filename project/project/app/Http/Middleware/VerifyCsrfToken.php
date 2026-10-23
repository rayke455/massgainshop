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
        'payment/*', // External payment gateway webhooks (Cryptomus, Coinbase, etc.)
        'mpesa/webhook', // Safaricom posts payment callbacks here (verified against their API before crediting)
        'snippe/webhook',
        'rentals/payout-result', // Safaricom B2C/B2B results // Snippe payment notifications (signature + API re-check before crediting)
    ];
}
