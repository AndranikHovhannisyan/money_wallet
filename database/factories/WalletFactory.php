<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Wallet::class, function (Faker $faker) {
    return [
        'name' => "Credit Card",
        'type' => "Cash"
    ];
});
