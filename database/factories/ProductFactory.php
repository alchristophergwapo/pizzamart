<?php

use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(App\Models\Products::class, function (Faker $faker) {
    return [
        //
        'productName' => $faker->name.' Pizza',
        'description'=> Str::random(10),
        'price'=> $faker->numberBetween(100,300),
        'imageUrl'=> 'banner.jpg',
        'sold'=> 0,
        'storeId'=>1
    ];
});
