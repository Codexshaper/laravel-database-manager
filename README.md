[![License](http://img.shields.io/:license-mit-blue.svg?style=flat-square)](http://badges.mit-license.org)
[![Build Status](https://travis-ci.org/Codexshaper/laravel-database-manager.svg?branch=master)](https://travis-ci.org/Codexshaper/laravel-database-manager)
[![Quality Score](https://img.shields.io/scrutinizer/g/Codexshaper/laravel-database-manager.svg?style=flat-square)](https://scrutinizer-ci.com/g/Codexshaper/laravel-database-manager)

# laravel-database-manager
Make your database simple and easyer

# Install package

```
composer require codexshaper/laravel-database-manager
```

# Setup database manager

```
php artisan dbm:install
```
# Setup Passport

Add `HasApiTokens` Trait in your `User` Model

```
<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
}
```
Next, you should call the `Passport::routes` method within the `boot` method of your `AuthServiceProvider`.
```
<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
```
Finally, in your `config/auth.php` configuration file, you should set the `driver` option of the `api` authentication guard to `passport`.
```
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'passport',
        'provider' => 'users',
    ],
],
```

# Create admin account to access all features

```
php artisan dbm:admin 'user' 'action' 'options'
```
Example
```
php artisan dbm:admin admin@admin.com create --columns=email
```

In this case ```email``` column must be exists in your users table and ```admin@admin.com``` must be a record