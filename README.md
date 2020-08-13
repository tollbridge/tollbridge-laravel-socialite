# Tollbridge.co service-provider for Laravel Socialite authentication

> THIS PACKAGE IS UNDER DEVELOPMENT, PLEASE USE WITH PRECAUTION.

![alt text](./tollbridge.png "Tollbridge.co")

[Tollbridge](https://tollbridge.co) is an user-authentication, subscription and paywall software as a service.

This package will help you to implement Tollbridge's based authentication in your [Laravel](https://laravel.com) application is just a few minutes.

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
TOLLBRIDGE_REDIRECT_URL=https://localhost/tollbridge/login-callback
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
        'callback' => '/tollbridge/login-callback',
    ],
];
```

## Usage

After install, just add the authentication routes to `/routes/web.php`

```php
use Tollbridge\Socialite\Support\TollbridgeAuth;

SupportTollbridgeAuth::routes();
```

To use a custom middleware on these routes, you can use a route-group like so:

```php
Route::middleware('guest')->group(function () {
    SupportTollbridgeAuth::routes();
});
```

To start the authentication process, add a link to the login URL:

```html
<a href="{{ url(config('tollbridge.routing.login')) }}">Login</a>
```

## Accessing the User's data

When the authentication process finishes successfully, an event is dispatched, you can create a new event-handler to get the user-data:

```php
<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [];

    public function boot()
    {
        parent::boot();

        Event::listen('Tollbridge\Socialite\Events\UserAuthenticatedEvent', function ($user) {
            User::updateOrCreate([
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'plan' => $user->getPlan()
            ]);
        });
    }
}
```
