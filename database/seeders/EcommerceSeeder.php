<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EcommerceSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user (only if not exists)
        if (!User::where('email', 'admin@admin.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
        }

        // Demo categories
        if (Category::count() === 0) {
            $categories = [
                ['name' => 'Beauty', 'slug' => 'beauty', 'description' => 'Beauty and skincare products', 'is_active' => true],
                ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Electronic devices', 'is_active' => true],
                ['name' => 'Clothing', 'slug' => 'clothing', 'description' => 'Fashion and apparel', 'is_active' => true],
                ['name' => 'Home & Garden', 'slug' => 'home-garden', 'description' => 'Home and garden items', 'is_active' => true],
                ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports equipment', 'is_active' => true],
                ['name' => 'Books', 'slug' => 'books', 'description' => 'Books and magazines', 'is_active' => true],
                ['name' => 'Toys', 'slug' => 'toys', 'description' => 'Toys and games', 'is_active' => true],
                ['name' => 'Automotive', 'slug' => 'automotive', 'description' => 'Car parts and accessories', 'is_active' => true],
            ];

            foreach ($categories as $cat) {
                Category::create($cat);
            }
        }

        // Demo products
        if (Product::count() === 0) {
            $beauty = Category::where('slug', 'beauty')->first();
            $products = [
                ['name' => 'Hydrating Facial Serum', 'category_id' => $beauty?->id ?? 1, 'price' => 29.99, 'sale_price' => 24.99, 'description' => 'Deep hydration serum with vitamin C.', 'short_description' => 'Vitamin C serum', 'stock' => 50, 'is_featured' => true, 'is_active' => true, 'image' => 'products/hydrating-facial-serum.jpg'],
                ['name' => 'Matte Lipstick Set', 'category_id' => $beauty?->id ?? 1, 'price' => 19.99, 'description' => 'Long-lasting matte lipstick collection.', 'short_description' => 'Matte lipstick', 'stock' => 80, 'is_featured' => true, 'is_active' => true, 'image' => 'products/matte-lipstick-set.jpg'],
                ['name' => 'Silk Pillowcase', 'category_id' => $beauty?->id ?? 1, 'price' => 34.50, 'sale_price' => 29.99, 'description' => 'Pure silk pillowcase for hair and skin.', 'short_description' => 'Silk pillowcase', 'stock' => 40, 'is_featured' => false, 'is_active' => true, 'image' => 'products/silk-pillowcase.jpg'],
                ['name' => 'Rose Water Toner', 'category_id' => $beauty?->id ?? 1, 'price' => 16.00, 'description' => 'Refreshing rose water facial toner.', 'short_description' => 'Rose toner', 'stock' => 60, 'is_featured' => false, 'is_active' => true, 'image' => 'products/rose-water-toner.jpg'],
                ['name' => 'Reusable Makeup Remover Pads', 'category_id' => $beauty?->id ?? 1, 'price' => 12.50, 'description' => 'Eco-friendly reusable makeup remover pads.', 'short_description' => 'Eco pads', 'stock' => 120, 'is_featured' => false, 'is_active' => true, 'image' => 'products/makeup-remover-pads.jpg'],
            ];

            foreach ($products as $product) {
                Product::create([
                    'name' => $product['name'],
                    'slug' => Str::slug($product['name']),
                    'category_id' => $product['category_id'],
                    'price' => $product['price'],
                    'sale_price' => $product['sale_price'] ?? null,
                    'description' => $product['description'],
                    'short_description' => $product['short_description'],
                    'stock' => $product['stock'],
                    'is_featured' => $product['is_featured'],
                    'is_active' => $product['is_active'],
                ]);
            }
        }
    }
}
