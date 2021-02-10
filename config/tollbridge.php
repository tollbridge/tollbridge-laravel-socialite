<?php

return [
    'app_id' => env('TOLLBRIDGE_APP_ID'),
    'client_id' => env('TOLLBRIDGE_CLIENT_ID'),
    'client_secret' => env('TOLLBRIDGE_CLIENT_SECRET'),
    'routing' => [
        'login' => '/tollbridge/login',
        'logout' => '/tollbridge/logout',
        'callback' => '/tollbridge/callback',
    ],
];
