<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tags;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'RTX Graphics'],
            ['name' => 'OLED Display'],
            ['name' => 'Touchscreen'],
            ['name' => 'Thunderbolt 4'],
            ['name' => 'Long Battery'],
            ['name' => 'Lightweight'],
            ['name' => 'Creator Ready'],
            ['name' => 'VR Ready'],
            ['name' => 'RGB Keyboard'],
            ['name' => 'Wi-Fi 7'],
            ['name' => 'Silent Cooling'],
            ['name' => 'Military Grade'],
            ['name' => 'Budget Friendly'],
            ['name' => 'Premium Finish'],
            ['name' => 'All Day Battery'],
            ['name' => 'Portable'],
            ['name' => 'High Refresh'],
            ['name' => 'AI Ready'],
            ['name' => 'Studio Certified'],
        ];

        Tags::insert($tags);
    }
}

