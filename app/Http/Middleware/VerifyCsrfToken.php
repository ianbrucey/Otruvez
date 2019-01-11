<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'location',
        'subscription/checkin/*',
        'subscription/confirmCheckin/*',
        '/stripeWebhook/failedPayment',
        '/business/checkHandleAvailability'
    ];
}
