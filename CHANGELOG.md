# Changelog

All notable changes to `laravel-bannable` will be documented in this file

## 2.3.0 - 2020-03-09
- Laravel 7.0 support

## 2.2.0 - 2019-09-18
- Laravel 6.0 support

## 2.1.0 - 2019-03-20

- Laravel 5.8 compatibility
- `forbidBannedUser` route middleware alias is removed. To protect routes, register `Qirolab\Laravel\Bannable\Middleware\ForbidBannedUser` middleware in `$routeMiddleware` array of `app/Http/Kernel.php` file.

## 2.0.0 - 2018-12-31

- package namespace changed from Hkp22 to Qirolab.
- package vendor name changed from hkp22 to qirolab. Now to install this package required new command to run `composer require qirolab/laravel-bannable`.


## 1.0.0 - 2018-07-10

- initial release

