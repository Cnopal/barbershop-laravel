<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Matte Clay Pomade',
                'description' => 'Strong hold clay pomade with a natural matte finish for textured modern hairstyles.',
                'category' => 'Styling',
                'image_url' => 'https://images.unsplash.com/photo-1621607512022-6aecc4fed814?auto=format&fit=crop&w=900&q=80',
                'price' => 38.00,
                'stock' => 24,
                'status' => 'active',
            ],
            [
                'name' => 'Classic Shine Pomade',
                'description' => 'Water-based pomade for slick backs, side parts, and polished classic looks.',
                'category' => 'Styling',
                'image_url' => 'https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?auto=format&fit=crop&w=900&q=80',
                'price' => 35.00,
                'stock' => 18,
                'status' => 'active',
            ],
            [
                'name' => 'Daily Hair Shampoo',
                'description' => 'Gentle daily shampoo that cleans hair and scalp without stripping natural moisture.',
                'category' => 'Hair Care',
                'image_url' => 'https://images.unsplash.com/photo-1556229010-6c3f2c9ca5f8?auto=format&fit=crop&w=900&q=80',
                'price' => 29.00,
                'stock' => 30,
                'status' => 'active',
            ],
            [
                'name' => 'Scalp Cooling Tonic',
                'description' => 'Refreshing scalp tonic with a cooling feel after a fresh cut or shampoo.',
                'category' => 'Hair Care',
                'image_url' => 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?auto=format&fit=crop&w=900&q=80',
                'price' => 42.00,
                'stock' => 16,
                'status' => 'active',
            ],
            [
                'name' => 'Beard Oil Cedar Blend',
                'description' => 'Lightweight beard oil that softens facial hair and adds a clean cedar scent.',
                'category' => 'Beard Care',
                'image_url' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=900&q=80',
                'price' => 32.00,
                'stock' => 22,
                'status' => 'active',
            ],
            [
                'name' => 'Beard Balm Hold & Shape',
                'description' => 'Conditioning balm for shaping medium to long beards with soft natural hold.',
                'category' => 'Beard Care',
                'image_url' => 'https://images.unsplash.com/photo-1621605815971-fbc98d665033?auto=format&fit=crop&w=900&q=80',
                'price' => 34.00,
                'stock' => 20,
                'status' => 'active',
            ],
            [
                'name' => 'Aftershave Splash',
                'description' => 'Clean aftershave splash that helps calm skin after razor work.',
                'category' => 'Shaving',
                'image_url' => 'https://images.unsplash.com/photo-1583241800698-9bba0ee7f4ac?auto=format&fit=crop&w=900&q=80',
                'price' => 27.00,
                'stock' => 15,
                'status' => 'active',
            ],
            [
                'name' => 'Shaving Cream Tube',
                'description' => 'Rich shaving cream for smooth razor glide and comfortable close shaves.',
                'category' => 'Shaving',
                'image_url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=900&q=80',
                'price' => 25.00,
                'stock' => 28,
                'status' => 'active',
            ],
            [
                'name' => 'Barber Styling Comb',
                'description' => 'Durable fine-tooth styling comb for clean sections, fades, and everyday grooming.',
                'category' => 'Tools',
                'image_url' => 'https://images.unsplash.com/photo-1580618672591-eb180b1a973f?auto=format&fit=crop&w=900&q=80',
                'price' => 12.00,
                'stock' => 40,
                'status' => 'active',
            ],
            [
                'name' => 'Round Styling Brush',
                'description' => 'Medium round brush for blow-drying volume, shape, and controlled styling.',
                'category' => 'Tools',
                'image_url' => 'https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?auto=format&fit=crop&w=900&q=80',
                'price' => 22.00,
                'stock' => 14,
                'status' => 'active',
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}
