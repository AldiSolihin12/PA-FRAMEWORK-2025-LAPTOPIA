<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Tags;

class ProductTagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = Tags::all();

        // Setiap produk punya 1-3 tag acak
        Product::all()->each(function ($product) use ($tags) {
            $product->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
