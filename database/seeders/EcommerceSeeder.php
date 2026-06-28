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
                ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Electronic devices', 'is_active' => true],
                ['name' => 'Clothing', 'slug' => 'clothing', 'description' => 'Fashion and apparel', 'is_active' => true],
                ['name' => 'Home & Garden', 'slug' => 'home-garden', 'description' => 'Home and garden items', 'is_active' => true],
                ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports equipment', 'is_active' => true],
                ['name' => 'Books', 'slug' => 'books', 'description' => 'Books and magazines', 'is_active' => true],
                ['name' => 'Toys', 'slug' => 'toys', 'description' => 'Toys and games', 'is_active' => true],
                ['name' => 'Beauty', 'slug' => 'beauty', 'description' => 'Beauty products', 'is_active' => true],
                ['name' => 'Automotive', 'slug' => 'automotive', 'description' => 'Car parts and accessories', 'is_active' => true],
            ];

            foreach ($categories as $cat) {
                Category::create($cat);
            }
        }

        // Demo products
        if (Product::count() === 0) {
            $products = [
                ['name' => 'Samsung 65" 4K TV', 'category_id' => 1, 'price' => 500, 'sale_price' => 499, 'description' => '4K Ultra HD Smart TV with HDR.', 'short_description' => '4K Ultra HD', 'stock' => 20, 'is_featured' => true, 'is_active' => true, 'image' => 'products/samsung-tv.jpg'],
                ['name' => 'Apple iPad Pro', 'category_id' => 1, 'price' => 1199, 'description' => 'The most powerful iPad ever.', 'short_description' => 'Powerful tablet', 'stock' => 15, 'is_featured' => true, 'is_active' => true, 'image' => 'products/apple-ipad-pro.jpg'],
                ['name' => 'Samsung Galaxy Watch', 'category_id' => 1, 'price' => 150, 'description' => 'Smartwatch with fitness tracking.', 'short_description' => 'Smart watch', 'stock' => 30, 'is_featured' => false, 'is_active' => true, 'image' => 'products/samsung-galaxy-watch.jpg'],
                ['name' => 'HP Spectre x360', 'category_id' => 1, 'price' => 820, 'description' => 'Premium convertible laptop.', 'short_description' => 'Convertible laptop', 'stock' => 10, 'is_featured' => true, 'is_active' => true, 'image' => 'products/hp-spectre-x360.jpg'],
                ['name' => 'JBL Bluetooth Speaker', 'category_id' => 1, 'price' => 120, 'description' => 'Portable bluetooth speaker.', 'short_description' => 'Portable speaker', 'stock' => 50, 'is_featured' => false, 'is_active' => true, 'image' => 'products/jbl-bluetooth-speaker.jpg'],
                ['name' => 'Casual T-Shirt', 'category_id' => 2, 'price' => 25, 'description' => 'Comfortable cotton t-shirt.', 'short_description' => 'Cotton tee', 'stock' => 100, 'is_featured' => false, 'is_active' => true, 'image' => 'products/casual-t-shirt.jpg'],
                ['name' => 'Running Shoes', 'category_id' => 4, 'price' => 89, 'description' => 'Lightweight running shoes.', 'short_description' => 'Lightweight', 'stock' => 40, 'is_featured' => true, 'is_active' => true, 'image' => 'products/running-shoes.jpg'],
                ['name' => 'The Art of Code', 'category_id' => 5, 'price' => 35, 'description' => 'Programming best practices book.', 'short_description' => 'Learn coding', 'stock' => 25, 'is_featured' => false, 'is_active' => true, 'image' => 'products/the-art-of-code.jpg'],
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
