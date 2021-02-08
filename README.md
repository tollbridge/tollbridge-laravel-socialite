# Tollbridge OAuth2 Provider for Laravel Socialite

[Tollbridge](https://tollbridge.co) is a user-authentication, subscription and paywall software as a service. This package will help you to implement Tollbridge's based authentication in your [Laravel](https://laravel.com) application is just a few minutes.

## Installation

Install via composer

```bash
composer require tollbridge/laravel-socialite
```

## Configuration

Add the credentials provided on the Tollbridge platform to your `.env` file:

```text
TOLLBRIDGE_CLIENT_ID=
TOLLBRIDGE_CLIENT_SECRET=
TOLLBRIDGE_REDIRECT_URL=https://localhost/tollbridge/callback
TOLLBRIDGE_ACCOUNT_URL=https://your-account.tollbridge.co
```

You can publish the configuration file to the local project directory using artisan:

```text
php artisan vendor:publish --tag=tollbridge-config
```

This is the content of the config file:

```php
<?php

return [
    'account_url' => env('TOLLBRIDGE_ACCOUNT_URL'),
    'client_id' => env('TOLLBRIDGE_CLIENT_ID'),
    'client_secret' => env('TOLLBRIDGE_CLIENT_SECRET'),
    'redirect' => env('TOLLBRIDGE_REDIRECT_URL'),
    'routing' => [
        'login' => '/tollbridge/login',
        'logout' => '/tollbridge/logout',
        'callback' => '/tollbridge/callback',
    ],
];
```

## Usage

After install, just add the authentication routes to `/routes/web.php`

```php
Route::get(config('tollbridge.routing.login'), function () {
    //session()->set('url.intended', request()->input('url'));
    //..
    return Socialite::with('tollbridge')->redirect();
});

Route::get(config('tollbridge.routing.logout'), function () {
    //session()->flush();
    //..
    return redirect()->intended();
});

Route::get(config('tollbridge.routing.callback'), function () {
    $user = Socialite::with('tollbridge')->user();
    //session()->set('user', $user);
    //..
    return redirect()->intended();
});
```

To start the authentication process, add a link to the login URL:

```html
<a href="{{ url(config('tollbridge.routing.login')) }}">Login</a>
```

## Included Middleware

The `Tollbridge\Socialite\Middleware\TollbridgeRedirects` middleware is automatically loaded.

If the param `_tollbridge_logout` is set the user will get redirected to `config('tollbridge.routing.logout')`

If the param `_tollbridge_reauth` is set the user will get redirected to `config('tollbridge.routing.login')`. 
This will in turn re-initiate an OAuth session.


## Local Development

First link up your local repository:

```bash
composer config repositories.local '{"type": "path", "url": "../tollbridge-laravel-socialite"}' --file composer.json
```

Then install as normal via composer:

```bash
composer require tollbridge/laravel-socialite
```
