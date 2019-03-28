<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model'             => App\User::class,
//        'public'            => env('STRIPE_KEY','pk_test_GQCUjPm3eovgzCUVD2RmzTjU'),
//        'secret'            => env('STRIPE_SECRET','sk_test_nsFME4mazT714NbSa8IDpejm'),
        'public'            => env('STRIPE_KEY','pk_live_A4ihazIvy0r1OQxucdQanpp1'),
        'secret'            => env('STRIPE_SECRET','sk_live_YtBdDOks5WQHsjRp2ezqFUTr'),
        \App\StripeWebhook::PAYMENT_FAILED_WH_KEY       => env('WEBHOOK_KEY','whsec_Z1z2IVZ9qJL012EwnAvbczRFizlOBK0E'),
        \App\StripeWebhook::PAYMENT_SUCCEEDED_WH_KEY    => env('WEBHOOK_KEY','whsec_4kwZz7SAJIHfuJuafwqLkU3bO5phChJY'),

    ],

    'search' => [
        'enabled' => env('SEARCH_ENABLED', true),
        'hosts' => explode(',', env('SEARCH_HOSTS')),
    ],

    'facebook' => [
        'client_id'     => env('FACEBOOK_ID'),
        'client_secret' => env('FACEBOOK_SECRET'),
        'redirect'      => env('FACEBOOK_CALLBACK_URL'),
        'portal-redirect'      => env('FACEBOOK_CALLBACK_URL_PORTAL')
    ],
];
