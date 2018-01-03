<?php

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $product = new \App\Product([
            'title' => 'pepeni',
            'description' => 'sunt mari',
            'price' => 33
        ]);
        $product->save();
    }
}
