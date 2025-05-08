<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Bộ PC', 'order' => 1],
            ['name' => 'Laptop', 'order' => 2],
            ['name' => 'MAIN', 'order' => 3],
            ['name' => 'CPU', 'order' => 4],
            ['name' => 'RAM', 'order' => 5],
            ['name' => 'VGA', 'order' => 6],
            ['name' => 'Ổ cứng', 'order' => 7],
            ['name' => 'PSU', 'order' => 8],
            ['name' => 'Case', 'order' => 9],
            ['name' => 'Màn hình', 'order' => 10],
            ['name' => 'Gaming Gear', 'order' => 11],
            ['name' => 'Tản Nhiệt', 'order' => 12],
            ['name' => 'Hàng Thanh Lý', 'order' => 13],
            ['name' => 'Tivi', 'order' => 14],
            ['name' => 'Phụ Kiện', 'order' => 15],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
