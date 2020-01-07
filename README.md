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

# Create admin account to access all features

```
php artisan dbm:admin 'user' 'action' 'options'
```
Example
```
php artisan admin@admin.com create --columns=email
```

In this case ```email``` must be exists in your users table and ```admin@admin.com``` must be a record