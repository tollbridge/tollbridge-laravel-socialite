<?php

return [
    'account_url' => env('TOLLBRIDGE_ACCOUNT_URL'),
    'client_id' => env('TOLLBRIDGE_CLIENT_ID'),
    'client_secret' => env('TOLLBRIDGE_CLIENT_SECRET'),
    'redirect' => env('TOLLBRIDGE_REDIRECT_URL'),
    'routing' => [
        'login' => '/tollbridge/login',
        'callback' => '/tollbridge/callback',
    ],
];
