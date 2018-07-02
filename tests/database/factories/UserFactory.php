<?php


use Faker\Generator as Faker;
use Hkp22\Tests\Laravel\Bannable\Stubs\Models\User;

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
