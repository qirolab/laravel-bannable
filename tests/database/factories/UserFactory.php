<?php


use Faker\Generator as Faker;
use Qirolab\Tests\Laravel\Bannable\Stubs\Models\User;
use Qirolab\Tests\Laravel\Bannable\Stubs\Models\UserWithoutBannableTrait;

/*
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});

/*
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(UserWithoutBannableTrait::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
