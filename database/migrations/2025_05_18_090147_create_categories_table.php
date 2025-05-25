<?php

use App\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('icon')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
        });

        // Danh sách danh mục kèm icon (PascalCase từ Lucide)
        $categories = [
            'Bộ PC' => ['icon' => 'https://nzxt.com/assets/cms/34299/1681919351-h7-flow-rgb-hero-black.png?auto=format&fit=crop&h=1000&w=1000', 'tags' => []],
            'Mainboard' => ['icon' => 'https://nzxt.com/assets/cms/34299/1681919351-h7-flow-rgb-hero-black.png?auto=format&fit=crop&h=1000&w=1000', 'tags' => ['socket', 'ram_gen', 'form_factor']],
            'CPU' => ['icon' => 'https://nzxt.com/assets/cms/34299/1681919351-h7-flow-rgb-hero-black.png?auto=format&fit=crop&h=1000&w=1000', 'tags' => ['socket', 'ram_gen']],
            'RAM' => ['icon' => 'https://nzxt.com/assets/cms/34299/1681919351-h7-flow-rgb-hero-black.png?auto=format&fit=crop&h=1000&w=1000', 'tags' => ['ram_gen']],
            'VGA' => ['icon' => 'https://nzxt.com/assets/cms/34299/1681919351-h7-flow-rgb-hero-black.png?auto=format&fit=crop&h=1000&w=1000', 'tags' => []],
            'Ổ cứng' => ['icon' => 'https://nzxt.com/assets/cms/34299/1681919351-h7-flow-rgb-hero-black.png?auto=format&fit=crop&h=1000&w=1000', 'tags' => []],
            'PSU' => ['icon' => 'https://nzxt.com/assets/cms/34299/1681919351-h7-flow-rgb-hero-black.png?auto=format&fit=crop&h=1000&w=1000', 'tags' => []],
            'CASE' => ['icon' => 'https://nzxt.com/assets/cms/34299/1681919351-h7-flow-rgb-hero-black.png?auto=format&fit=crop&h=1000&w=1000', 'tags' => ['form_factor']],
            'Tản nhiệt' => ['icon' => 'https://nzxt.com/assets/cms/34299/1681919351-h7-flow-rgb-hero-black.png?auto=format&fit=crop&h=1000&w=1000', 'tags' => []],
            'Màn hình' => ['icon' => 'https://nzxt.com/assets/cms/34299/1681919351-h7-flow-rgb-hero-black.png?auto=format&fit=crop&h=1000&w=1000', 'tags' => []],
            'Gaming gear' => ['icon' => 'https://nzxt.com/assets/cms/34299/1681919351-h7-flow-rgb-hero-black.png?auto=format&fit=crop&h=1000&w=1000', 'tags' => []],
            'Thanh lý' => ['icon' => 'https://nzxt.com/assets/cms/34299/1681919351-h7-flow-rgb-hero-black.png?auto=format&fit=crop&h=1000&w=1000', 'tags' => []],
        ];

        $data = [];

        foreach ($categories as $name => $meta) {
            $i = 1;
            $data[] = [
                'name' => $name,
                'slug' => Str::slug($name). '-c.' . $i++,
                'icon' => $meta['icon'],
                'tags' => json_encode($meta['tags']),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach ($data as $item) {
            Category::create($item);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
