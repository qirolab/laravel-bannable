# Laravel Bannable [![GitHub release](https://img.shields.io/github/release/qirolab/laravel-bannable.svg?style=flat-square)](https://github.com/qirolab/laravel-bannable) [![Travis (.org)](https://img.shields.io/travis/qirolab/laravel-bannable.svg?style=flat-square)](https://github.com/qirolab/laravel-bannable) [![StyleCI](https://github.styleci.io/repos/140012721/shield?branch=master)](https://github.styleci.io/repos/140012721) [![GitHub](https://img.shields.io/github/license/qirolab/laravel-bannable.svg)](https://github.com/qirolab/laravel-bannable)

Laravel bannable package for blocking and banning Eloquent models. Using this package any model can be made bannable such as Organizations, Teams, Groups, and others.

## Installation

Download package into the project using Composer.

```bash
composer require qirolab/laravel-bannable
```

### Registering package
> Laravel 5.5 (or higher) uses Package Auto-Discovery, so doesn't require you to manually add the ServiceProvider.

For Laravel 5.4 or earlier releases version include the service provider within `app/config/app.php`:

```php
'providers' => [
    Qirolab\Laravel\Bannable\BannableServiceProvider::class,
],
```

### Prepare Migration
Now need to add nullable `banned_at` timestamp column to model. So, create a new migration file.

```bash
php artisan make:migration add_banned_at_column_to_users_table
```

Add `$table->timestamp('banned_at')->nullable();` in this new migration file as in below example.

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBannedAtColumnToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('banned_at')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('banned_at');
        });
    }
}
```

Now run migration.
```bash
php artisan migrate
```

### Prepare bannable model
Use `Bannable` trait in the Model as in below example.

```
use Qirolab\Laravel\Bannable\Traits\Bannable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Bannable;
}
```

## Available methods

### Ban model entity.
```php
$user->ban();
```

### Ban model entity with reason comment
```php
$user->ban([
    'comment' => 'ban comment!',
]);
```

### Ban model entity with expire time

Here `expired_at` attribute could be `\Carbon\Carbon` instance or any time string which could be parsed by \Carbon\Carbon::parse($string) method:
```php
$user->ban([
    'expired_at' => '2086-03-28 00:00:00',
]);
```
or

```php
$user->ban([
    'expired_at' => '+1 year',
]);
```

or

```php
$date = Carbon::now()->addWeeks(2);

$user->ban([
    'expired_at' => $date,
]);
```

### Remove ban model
On `unban` all related ban models are soft deletes.

```php
$user->unban();
```

### Check if entity is banned
```php
$user->isBanned();
```

### Check if entity is not banned
```php
$user->isNotBanned();
```

### Delete expired bans manually
```php
Qirolab\Laravel\Bannable\Models\Ban::deleteExpired();
```

## Scopes

### Get all models which are not banned
```php
$users = User::withoutBanned()->get();
```

### Get banned and not banned models
```php
$users = User::withBanned()->get();
```

### Get only banned models
```php
$users = User::onlyBanned()->get();
```

### Disable scope that hides banned models entity by default

By default all banned models will be hidden. To disable query scopes all the time you can define `disableBannedAtScope` method in bannable model.

```php
/**
 * Determine which BannedAtScope should be applied or not.
 *
 * @return bool
 */
public function disableBannedAtScope()
{
    return true;
}
```

## Events

On model entity ban `\Qirolab\Laravel\Bannable\Events\ModelWasBanned` event is fired.

On model entity unban `\Qirolab\Laravel\Bannable\Events\ModelWasUnbanned` event is fired.

## Middleware
This package has `forbidBannedUser` route middleware to restrict banned users to go to protected routes.

```php
Route::get('/dashboard', 'DashboardController@index')->middleware('forbidBannedUser');
```

