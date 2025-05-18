<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->bigInteger('price');
            $table->bigInteger('import_price');
            $table->bigInteger('line_price');
            $table->integer('stock')->default(0);
            $table->json('images')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->json('specs')->nullable();
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        $cpus = [
            [
                'name' => 'Intel Core i5 12400F',
                'slug' => 'intel-core-i5-12400f-p.1',
                'description' => 'CPU Intel Core i5 thế hệ 12, 6 nhân 12 luồng, tốc độ 2.5GHz - 4.4GHz, socket LGA1700',
                'price' => 2500000,
                'import_price' => 2000000,
                'line_price' => 2990000,
                'stock' => 1000,
                'images' => json_encode(['/products/cpu/i512th.jpg', '/products/pc.png']),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1700',
                    'cores' => 6,
                    'threads' => 12,
                    'base_clock' => '2.5GHz',
                    'boost_clock' => '4.4GHz',
                    'cache' => '18MB',
                    'tdp' => '65W'
                ]),
                'brand_id' => 1,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel Core i7 13700K',
                'slug' => 'intel-core-i7-13700k-p.2',
                'description' => 'CPU Intel Core i7 thế hệ 13, 16 nhân 24 luồng, tốc độ 3.4GHz - 5.4GHz, socket LGA1700',
                'price' => 8990000,
                'import_price' => 7500000,
                'line_price' => 9990000,
                'stock' => 500,
                'images' => json_encode(['/products/cpu/i512th.jpg', '/products/pc.png']),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1700',
                    'cores' => 16,
                    'threads' => 24,
                    'base_clock' => '3.4GHz',
                    'boost_clock' => '5.4GHz',
                    'cache' => '30MB',
                    'tdp' => '125W'
                ]),
                'brand_id' => 1,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel Core i9 13900K',
                'slug' => 'intel-core-i9-13900k-p.3',
                'description' => 'CPU Intel Core i9 thế hệ 13 flagship, 24 nhân 32 luồng, tốc độ 3.0GHz - 5.8GHz',
                'price' => 12990000,
                'import_price' => 11000000,
                'line_price' => 14990000,
                'stock' => 200,
                'images' => json_encode(['/products/cpu/i512th.jpg', '/products/pc.png']),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1700',
                    'cores' => 24,
                    'threads' => 32,
                    'base_clock' => '3.0GHz',
                    'boost_clock' => '5.8GHz',
                    'cache' => '36MB',
                    'tdp' => '125W'
                ]),
                'brand_id' => 1,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel Core i3 12100F',
                'slug' => 'intel-core-i3-12100f-p.4',
                'description' => 'CPU Intel Core i3 thế hệ 12 giá rẻ, 4 nhân 8 luồng, tốc độ 3.3GHz - 4.3GHz',
                'price' => 1890000,
                'import_price' => 1500000,
                'line_price' => 2290000,
                'stock' => 1500,
                'images' => json_encode(['/products/cpu/i512th.jpg', '/products/pc.png']),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1700',
                    'cores' => 4,
                    'threads' => 8,
                    'base_clock' => '3.3GHz',
                    'boost_clock' => '4.3GHz',
                    'cache' => '12MB',
                    'tdp' => '58W'
                ]),
                'brand_id' => 1,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel Core i5 13600K',
                'slug' => 'intel-core-i5-13600k-p.5',
                'description' => 'CPU Intel Core i5 thế hệ 13, 14 nhân 20 luồng, tốc độ 3.5GHz - 5.1GHz',
                'price' => 6990000,
                'import_price' => 6000000,
                'line_price' => 7990000,
                'stock' => 700,
                'images' => json_encode(['/products/cpu/i512th.jpg', '/products/pc.png']),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1700',
                    'cores' => 14,
                    'threads' => 20,
                    'base_clock' => '3.5GHz',
                    'boost_clock' => '5.1GHz',
                    'cache' => '24MB',
                    'tdp' => '125W'
                ]),
                'brand_id' => 1,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel Core i7 12700K',
                'slug' => 'intel-core-i7-12700k-p.6',
                'description' => 'CPU Intel Core i7 thế hệ 12, 12 nhân 20 luồng, tốc độ 3.6GHz - 5.0GHz',
                'price' => 7990000,
                'import_price' => 6800000,
                'line_price' => 8990000,
                'stock' => 400,
                'images' => json_encode(['/products/cpu/i512th.jpg', '/products/pc.png']),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1700',
                    'cores' => 12,
                    'threads' => 20,
                    'base_clock' => '3.6GHz',
                    'boost_clock' => '5.0GHz',
                    'cache' => '25MB',
                    'tdp' => '125W'
                ]),
                'brand_id' => 1,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel Core i9 12900K',
                'slug' => 'intel-core-i9-12900k-p.7',
                'description' => 'CPU Intel Core i9 thế hệ 12 flagship, 16 nhân 24 luồng, tốc độ 3.2GHz - 5.2GHz',
                'price' => 11990000,
                'import_price' => 10000000,
                'line_price' => 13990000,
                'stock' => 150,
                'images' => json_encode(['/products/cpu/i512th.jpg', '/products/pc.png']),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1700',
                    'cores' => 16,
                    'threads' => 24,
                    'base_clock' => '3.2GHz',
                    'boost_clock' => '5.2GHz',
                    'cache' => '30MB',
                    'tdp' => '125W'
                ]),
                'brand_id' => 1,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel Core i5 12600K',
                'slug' => 'intel-core-i5-12600k-p.8',
                'description' => 'CPU Intel Core i5 thế hệ 12, 10 nhân 16 luồng, tốc độ 3.7GHz - 4.9GHz',
                'price' => 5990000,
                'import_price' => 5000000,
                'line_price' => 6990000,
                'stock' => 600,
                'images' => json_encode(['/products/cpu/i512th.jpg', '/products/pc.png']),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1700',
                    'cores' => 10,
                    'threads' => 16,
                    'base_clock' => '3.7GHz',
                    'boost_clock' => '4.9GHz',
                    'cache' => '20MB',
                    'tdp' => '125W'
                ]),
                'brand_id' => 1,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel Core i3 13100F',
                'slug' => 'intel-core-i3-13100f-p.9',
                'description' => 'CPU Intel Core i3 thế hệ 13 giá rẻ, 4 nhân 8 luồng, tốc độ 3.4GHz - 4.5GHz',
                'price' => 2290000,
                'import_price' => 1900000,
                'line_price' => 2790000,
                'stock' => 1200,
                'images' => json_encode(['/products/cpu/i512th.jpg', '/products/pc.png']),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1700',
                    'cores' => 4,
                    'threads' => 8,
                    'base_clock' => '3.4GHz',
                    'boost_clock' => '4.5GHz',
                    'cache' => '12MB',
                    'tdp' => '58W'
                ]),
                'brand_id' => 1,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel Core i7 14700K',
                'slug' => 'intel-core-i7-14700k-p.10',
                'description' => 'CPU Intel Core i7 thế hệ 14 mới nhất, 20 nhân 28 luồng, tốc độ 3.4GHz - 5.6GHz',
                'price' => 9990000,
                'import_price' => 8500000,
                'line_price' => 11990000,
                'stock' => 300,
                'images' => json_encode(['/products/cpu/i512th.jpg', '/products/pc.png']),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1700',
                    'cores' => 20,
                    'threads' => 28,
                    'base_clock' => '3.4GHz',
                    'boost_clock' => '5.6GHz',
                    'cache' => '33MB',
                    'tdp' => '125W'
                ]),
                'brand_id' => 1,
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('products')->insert($cpus);
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
