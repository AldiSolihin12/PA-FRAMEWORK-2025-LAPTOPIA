<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Laptopia Spectra 14',
                'brand' => 'Laptopia',
                'price' => 1499,
                'stock' => 25,
                'image' => 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?q=80&w=1200&auto=format&fit=crop',
                'category_id' => 1,
            ],
            [
                'name' => 'Acer Predator Helios Neo',
                'brand' => 'Acer',
                'price' => 1899,
                'stock' => 18,
                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?q=80&w=1200&auto=format&fit=crop',
                'category_id' => 2,
            ],
            [
                'name' => 'ASUS ZenBook Pro 16X',
                'brand' => 'ASUS',
                'price' => 2399,
                'stock' => 12,
                'image' => 'https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?q=80&w=1200&auto=format&fit=crop',
                'category_id' => 4,
            ],
            [
                'name' => 'MSI Stealth 16 Studio',
                'brand' => 'MSI',
                'price' => 2099,
                'stock' => 20,
                'image' => 'https://images.unsplash.com/photo-1588524806570-8b7094c042d4?q=80&w=1200&auto=format&fit=crop',
                'category_id' => 7,
            ],
            [
                'name' => 'Lenovo Yoga 9i',
                'brand' => 'Lenovo',
                'price' => 1799,
                'stock' => 30,
                'image' => 'https://images.unsplash.com/photo-1569228488691-3e94a47d8f5d?q=80&w=1200&auto=format&fit=crop',
                'category_id' => 5,
            ],
            [
                'name' => 'HP Spectre x360',
                'brand' => 'HP',
                'price' => 1699,
                'stock' => 28,
                'image' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?q=80&w=1200&auto=format&fit=crop',
                'category_id' => 5,
            ],
            [
                'name' => 'Apple MacBook Pro 16 M4',
                'brand' => 'Apple',
                'price' => 3299,
                'stock' => 15,
                'image' => 'https://images.unsplash.com/photo-1504703395950-b89145a5425b?q=80&w=1200&auto=format&fit=crop',
                'category_id' => 10,
            ],
            [
                'name' => 'Dell XPS 13 Plus',
                'brand' => 'Dell',
                'price' => 1599,
                'stock' => 22,
                'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?q=80&w=1200&auto=format&fit=crop',
                'category_id' => 11,
            ],
            [
                'name' => 'Razer Blade 18',
                'brand' => 'Razer',
                'price' => 3199,
                'stock' => 10,
                'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?q=80&w=1200&auto=format&fit=crop',
                'category_id' => 2,
            ],
            [
                'name' => 'Microsoft Surface Laptop 6',
                'brand' => 'Microsoft',
                'price' => 1999,
                'stock' => 26,
                'image' => 'https://images.unsplash.com/photo-1480694313141-fce5e697ee25?q=80&w=1200&auto=format&fit=crop',
                'category_id' => 3,
            ],
            [
                'name' => 'Gigabyte Aero 15',
                'brand' => 'Gigabyte',
                'price' => 2149,
                'stock' => 17,
                'image' => 'https://images.unsplash.com/photo-1488590528505-98d2b5aba04b?q=80&w=1200&auto=format&fit=crop',
                'category_id' => 4,
            ],
            [
                'name' => 'Acer Swift Go 14',
                'brand' => 'Acer',
                'price' => 1099,
                'stock' => 40,
                'image' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=1200&auto=format&fit=crop',
                'category_id' => 12,
            ],
        ];

        foreach ($products as $product) {
            $slug = Str::slug($product['name']);

            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $product['name'],
                    'brand' => $product['brand'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'image' => $product['image'],
                    'category_id' => $product['category_id'],
                ]
            );
        }
    }
}
