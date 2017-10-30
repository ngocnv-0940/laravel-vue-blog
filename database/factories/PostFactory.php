<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Post::class, function (Faker $faker) {
    return [
        'user_id' => App\Models\User::pluck('id')->random(),
        'category_id' => App\Models\Category::where('parent_id', '<>', null)->pluck('id')->random(),
        'title' => $title = $faker->unique()->sentence,
        'slug' => str_slug($title),
        'excerpt' => $faker->text,
        'content' => $faker->text(1000),
        'image' => $faker->imageUrl(640, 480,'nature'),
        'featured' => rand(0, 1),
    ];
});
