<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Like::class, function (Faker $faker) {
    return [
        'user_id' => \App\Models\User::pluck('id')->random()
    ];
});
