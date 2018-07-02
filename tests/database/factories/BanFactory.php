<?php


use Faker\Generator as Faker;
use Hkp22\Laravel\Bannable\Models\Ban;
use Hkp22\Tests\Laravel\Bannable\Stubs\Models\User;

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 */
$factory->define(Ban::class, function (Faker $faker) {
    $bannable = factory(User::class)->create();

    return [
        'bannable_id' => $bannable->getKey(),
        'bannable_type' => $bannable->getMorphClass(),
    ];
});
