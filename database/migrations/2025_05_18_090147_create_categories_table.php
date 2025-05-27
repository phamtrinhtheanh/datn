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
            'Bộ PC' => ['icon' => '/categories/bopc.png', 'tags' => []],
            'Mainboard' => ['icon' => '/categories/mainboard.png', 'tags' => ['socket', 'ram_gen', 'form_factor']],
            'CPU' => ['icon' => '/categories/cpu.png', 'tags' => ['socket', 'ram_gen']],
            'RAM' => ['icon' => '/categories/ram.png', 'tags' => ['ram_gen']],
            'VGA' => ['icon' => '/categories/vga.png', 'tags' => []],
            'Ổ cứng' => ['icon' => '/categories/ssd.png', 'tags' => []],
            'PSU' => ['icon' => '/categories/psu.png', 'tags' => []],
            'CASE' => ['icon' => '/categories/case.png', 'tags' => ['form_factor']],
            'Tản nhiệt' => ['icon' => '/categories/cooler.png', 'tags' => []],
            'Màn hình' => ['icon' => '/categories/monitor.png', 'tags' => []],
            'Gaming gear' => ['icon' => '/categories/gear.png', 'tags' => []],
            'Thanh lý' => ['icon' => '/categories/access.png', 'tags' => []],
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
