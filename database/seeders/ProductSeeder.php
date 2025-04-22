<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Razer BlackShark V2 Pro',
                'description' => 'Wireless Gaming Headset with THX 7.1 Spatial Surround Sound, 24hr Battery Life, and Memory Foam Ear Cushions',
                'price' => 179.99,
                'image_url' => 'images/products/blackshark.jpg',
                'stock' => 15
            ],
            [
                'name' => 'Logitech G Pro X Mechanical Keyboard',
                'description' => 'Tenkeyless Mechanical Gaming Keyboard with RGB Lighting, Hot-swappable Switches, and Aluminum Construction',
                'price' => 149.99,
                'image_url' => 'images/products/gpro-keyboard.jpg',
                'stock' => 20
            ],
            [
                'name' => 'Logitech G Pro X Superlight',
                'description' => 'Ultra-lightweight Wireless Gaming Mouse with HERO 25K Sensor and 70-hour Battery Life',
                'price' => 159.99,
                'image_url' => 'images/products/superlight.jpg',
                'stock' => 25
            ],
            [
                'name' => 'SteelSeries QcK Prism XL',
                'description' => 'RGB Gaming Mouse Pad with 2-Zone RGB Illumination and Micro-Woven Cloth Surface',
                'price' => 59.99,
                'image_url' => 'images/products/qck-prism.jpg',
                'stock' => 30
            ],
            [
                'name' => 'Blue Yeti X',
                'description' => 'Professional USB Microphone with High-Res LED Metering and Custom Blue VO!CE Effects',
                'price' => 169.99,
                'image_url' => 'images/products/yeti-x.jpg',
                'stock' => 12
            ],
            [
                'name' => 'ASUS ROG Swift 27" Monitor',
                'description' => '27-inch QHD (2560x1440) Gaming Monitor with 165Hz Refresh Rate and 1ms Response Time',
                'price' => 499.99,
                'image_url' => 'images/products/rog-swift.jpg',
                'stock' => 8
            ],
            [
                'name' => 'Elgato Stream Deck MK.2',
                'description' => '15-Key LCD Stream Control Pad with Customizable Buttons and Interchangeable Faceplates',
                'price' => 149.99,
                'image_url' => 'images/products/stream-deck.jpg',
                'stock' => 18
            ],
            [
                'name' => 'Razer Kiyo Pro',
                'description' => '1080p 60FPS Webcam with Adaptive Light Sensor and Wide-Angle Lens',
                'price' => 199.99,
                'image_url' => 'images/products/kiyo-pro.jpg',
                'stock' => 10
            ],
            [
                'name' => 'HyperX Cloud Alpha Wireless',
                'description' => 'Gaming Headset with up to 300 Hours of Battery Life and DTS Headphone:X',
                'price' => 199.99,
                'image_url' => 'images/products/cloud-alpha.jpg',
                'stock' => 22
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
