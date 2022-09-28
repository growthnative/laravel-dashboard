<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'google' => [
        'client_id' => '708808541121-2e4sbcqu0q2926gmh09v1hagosc3osdn.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'http://127.0.0.1:8000/callback/google',
    ], 
    'facebook' => [
        'client_id' => '766335071072179',
        'client_secret' => '353f72db892ac41fcc5469b706351f7e',
        'redirect' => 'http://localhost:8000/callback',
    ],
    'stripe' => [
        'secret' => env('STRIPE_SECRET'),
    ],

];
