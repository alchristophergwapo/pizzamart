<?php

use Illuminate\Database\Seeder;
use App\Models\Products;
use Lcobucci\JWT\Claim\Factory;

class PizzaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for ($i=1; $i < 16; $i++) { 
	    // 	Products::create([
        //         'productName'=>"product".$i,
        //         'description'=>"about product".$i,
        //         'price'=>80*$i,
        //         'imageUrl'=>"/images/banner.jpg",
        //         'storeId'=>1
	    //     ]);
        // }
        factory(Products::class , 20)->create();
    }
}
