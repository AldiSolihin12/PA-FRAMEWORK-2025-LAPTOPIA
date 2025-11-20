<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Database\Seeder;

class ProductDetailSeeder extends Seeder
{
    public function run(): void
    {
        $detailsByName = [
            'Laptopia Spectra 14' => [
                'description' => 'Premium ultrabook engineered for creators who demand a perfect balance of portability and performance.',
                'processor' => 'Intel Core Ultra 7 155H',
                'graphics' => 'Intel Arc Graphics 8-core',
                'ram' => '32GB LPDDR5X',
                'storage' => '1TB PCIe 4.0 SSD',
                'display' => '14" 3K OLED 120Hz',
                'battery' => '75Wh up to 16 hours',
                'weight' => '1.2 kg',
                'ports' => '2x Thunderbolt 4, HDMI 2.1, USB-A, SD Express',
                'operating_system' => 'Windows 11 Pro',
            ],
            'Acer Predator Helios Neo' => [
                'description' => 'High-refresh gaming laptop with advanced cooling and DLSS 3 performance.',
                'processor' => 'Intel Core i9-14900HX',
                'graphics' => 'NVIDIA GeForce RTX 4080 12GB',
                'ram' => '32GB DDR5-5600',
                'storage' => '2TB PCIe 4.0 SSD',
                'display' => '16" WQXGA 240Hz Mini LED',
                'battery' => '90Wh fast charge',
                'weight' => '2.4 kg',
                'ports' => 'Thunderbolt 4, HDMI 2.1, USB-C PD, RJ45',
                'operating_system' => 'Windows 11 Home',
            ],
            'ASUS ZenBook Pro 16X' => [
                'description' => 'Creator-focused laptop with ASUS Dial and Pantone-validated display.',
                'processor' => 'Intel Core Ultra 9 185H',
                'graphics' => 'NVIDIA GeForce RTX 4070 8GB',
                'ram' => '32GB LPDDR5X',
                'storage' => '2TB PCIe 4.0 SSD',
                'display' => '16" 3.2K OLED 120Hz',
                'battery' => '96Wh Rapid Charge',
                'weight' => '2.1 kg',
                'ports' => '2x Thunderbolt 4, USB-A, HDMI 2.1, SD Express',
                'operating_system' => 'Windows 11 Pro',
            ],
            'MSI Stealth 16 Studio' => [
                'description' => 'Studio-certified workstation blending stealth aesthetics with pro-grade GPUs.',
                'processor' => 'Intel Core i9-14900H',
                'graphics' => 'NVIDIA GeForce RTX 4070 Studio 8GB',
                'ram' => '64GB DDR5-5600',
                'storage' => '2TB PCIe 4.0 SSD',
                'display' => '16" QHD+ 120Hz Mini LED',
                'battery' => '99.9Wh USB-C PD',
                'weight' => '1.99 kg',
                'ports' => 'Thunderbolt 4, USB-C, HDMI 2.1, microSD',
                'operating_system' => 'Windows 11 Pro',
            ],
            'Lenovo Yoga 9i' => [
                'description' => '360° convertible with Dolby Atmos soundbar hinge and OLED visuals.',
                'processor' => 'Intel Core Ultra 7 155U',
                'graphics' => 'Intel Arc Graphics',
                'ram' => '16GB LPDDR5X',
                'storage' => '1TB PCIe 4.0 SSD',
                'display' => '14" 2.8K OLED Touch',
                'battery' => '75Wh Rapid Charge Express',
                'weight' => '1.4 kg',
                'ports' => '2x Thunderbolt 4, USB-A, USB-C',
                'operating_system' => 'Windows 11 Home',
            ],
            'HP Spectre x360' => [
                'description' => 'Convertible ultrabook with edge-to-edge keyboard and AI-enhanced webcam.',
                'processor' => 'Intel Core Ultra 7 155H',
                'graphics' => 'Intel Arc Graphics',
                'ram' => '32GB LPDDR5X',
                'storage' => '1TB PCIe 4.0 SSD',
                'display' => '14" 3K OLED Touch 120Hz',
                'battery' => '66Wh HP Fast Charge',
                'weight' => '1.36 kg',
                'ports' => '2x Thunderbolt 4, USB-A, microSD',
                'operating_system' => 'Windows 11 Home',
            ],
            'Apple MacBook Pro 16 M4' => [
                'description' => 'Apple silicon powerhouse with Neural Engine acceleration and long battery life.',
                'processor' => 'Apple M4 Pro 12-core',
                'graphics' => 'Apple M4 Pro 24-core GPU',
                'ram' => '32GB Unified Memory',
                'storage' => '1TB NVMe SSD',
                'display' => '16" Liquid Retina XDR 120Hz',
                'battery' => '100Wh up to 22 hours',
                'weight' => '2.16 kg',
                'ports' => '3x Thunderbolt 4, HDMI 2.1, SDXC, MagSafe 4',
                'operating_system' => 'macOS Sequoia',
            ],
            'Dell XPS 13 Plus' => [
                'description' => 'Bezel-less ultrabook with capacitive row and glass haptic touchpad.',
                'processor' => 'Intel Core Ultra 7 155H',
                'graphics' => 'Intel Arc Graphics',
                'ram' => '32GB LPDDR5X',
                'storage' => '1TB PCIe 4.0 SSD',
                'display' => '13.4" 3.5K OLED 120Hz',
                'battery' => '55Wh ExpressCharge 2.0',
                'weight' => '1.24 kg',
                'ports' => '2x Thunderbolt 4, USB-C hub included',
                'operating_system' => 'Windows 11 Pro',
            ],
            'Razer Blade 18' => [
                'description' => 'Desktop-class gaming laptop with 18-inch UHD+ 240Hz display.',
                'processor' => 'Intel Core i9-14900HX',
                'graphics' => 'NVIDIA GeForce RTX 4090 16GB',
                'ram' => '64GB DDR5-5600',
                'storage' => '2TB PCIe 4.0 SSD',
                'display' => '18" UHD+ Mini LED 240Hz',
                'battery' => '91.7Wh GaN fast charging',
                'weight' => '3.05 kg',
                'ports' => 'Thunderbolt 4, USB-C PD, HDMI 2.1, SD UHS-II',
                'operating_system' => 'Windows 11 Pro',
            ],
            'Microsoft Surface Laptop 6' => [
                'description' => 'Surface laptop with AI Copilot keys and PixelSense touchscreen.',
                'processor' => 'Intel Core Ultra 7 155U',
                'graphics' => 'Intel Arc Graphics',
                'ram' => '24GB LPDDR5X',
                'storage' => '1TB Removable SSD',
                'display' => '15" PixelSense 3:2 120Hz',
                'battery' => '55Wh up to 19 hours',
                'weight' => '1.56 kg',
                'ports' => '2x USB-C, USB-A, Surface Connect',
                'operating_system' => 'Windows 11 Pro',
            ],
            'Gigabyte Aero 15' => [
                'description' => 'Calibrated for Adobe RGB with factory Pantone validation.',
                'processor' => 'Intel Core i7-13700H',
                'graphics' => 'NVIDIA GeForce RTX 4070 8GB',
                'ram' => '32GB DDR5-5200',
                'storage' => '1TB PCIe 4.0 SSD',
                'display' => '15.6" 4K AMOLED HDR',
                'battery' => '99Wh',
                'weight' => '2.0 kg',
                'ports' => 'Thunderbolt 4, HDMI 2.1, USB-A, UHS-II SD',
                'operating_system' => 'Windows 11 Pro',
            ],
            'Acer Swift Go 14' => [
                'description' => 'Budget-friendly ultraportable with OLED display and Wi-Fi 7.',
                'processor' => 'Intel Core Ultra 5 125H',
                'graphics' => 'Intel Arc Graphics',
                'ram' => '16GB LPDDR5X',
                'storage' => '512GB PCIe 4.0 SSD',
                'display' => '14" 2.8K OLED 90Hz',
                'battery' => '65Wh fast charge',
                'weight' => '1.32 kg',
                'ports' => '2x USB-C, USB-A, HDMI 2.1',
                'operating_system' => 'Windows 11 Home',
            ],
        ];

        foreach (Product::all() as $product) {
            $spec = $detailsByName[$product->name] ?? [];

            ProductDetail::updateOrCreate(
                ['product_id' => $product->id],
                array_merge([
                    'description' => $spec['description'] ?? 'Powerful laptop designed for productivity and creativity.',
                    'processor' => $spec['processor'] ?? 'Intel Core Ultra 7 155H',
                    'graphics' => $spec['graphics'] ?? 'Intel Arc Graphics',
                    'ram' => $spec['ram'] ?? '16GB LPDDR5X',
                    'storage' => $spec['storage'] ?? '1TB PCIe 4.0 SSD',
                    'display' => $spec['display'] ?? '14" 2.8K OLED',
                    'battery' => $spec['battery'] ?? '70Wh rapid charge',
                    'weight' => $spec['weight'] ?? '1.4 kg',
                    'ports' => $spec['ports'] ?? '2x Thunderbolt 4, HDMI 2.1, USB-A',
                    'operating_system' => $spec['operating_system'] ?? 'Windows 11 Pro',
                ], $spec)
            );
        }
    }
}
