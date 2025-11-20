<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Ultrabook'],
            ['name' => 'Gaming Laptop'],
            ['name' => 'Business Laptop'],
            ['name' => 'Content Creator'],
            ['name' => '2-in-1 Convertible'],
            ['name' => 'Student Laptop'],
            ['name' => 'Workstation'],
            ['name' => 'Chromebook'],
            ['name' => 'Everyday Use'],
            ['name' => 'Premium Flagship'],
            ['name' => 'Thin & Light'],
            ['name' => 'Budget'],
        ];

        Category::insert($categories);
    }
}

