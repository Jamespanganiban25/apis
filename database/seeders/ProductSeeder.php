<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

 

    public function run()
    {

    	$faker = Faker::create();
    	foreach (range(1,10) as $index) {
	        DB::table('products')->insert([
	            'name' => $faker->word,
	            'stock' => rand(5,20)
        // DB::table('products')->insert([
        //     'name' => str::random(20),
        //     'stock' => rand(5,20)

        ]);    


	    }
        }
}
