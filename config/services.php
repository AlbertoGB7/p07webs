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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', '1020951802216-pg83jpfqovemvuj6ufvtjup1c7gae90g.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', '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'),
        'redirect' => env('GOOGLE_REDIRECT_URI', 'http://127.0.0.1:8000/auth/google/callback'),
    ],


    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID', 'Ov23liMycgL7pWUInzXN'),
        'client_secret' => env('GITHUB_CLIENT_SECRET', '6e2a4700ebbd38e21789a47dc98ff7f3635a0aac'),
        'redirect' => env('GITHUB_REDIRECT_URI', 'http://127.0.0.1:8000/auth/github/callback'),
    ],

];
