<?php

use App\Models\Product;
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
            $table->integer('sold')->default(0);
            $table->float('rating')->default(5)->nullable();
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
                'name' => 'Intel Core Ultra 5 245K',
                'slug' => 'intel-core-ultra-5-245k-p.1',
                'description' => 'CPU Intel Core Ultra 5 thế hệ mới, 14 nhân 18 luồng (6P + 8E), tốc độ 3.6GHz - 5.5GHz, hỗ trợ AI NPU, DDR5-6000, PCIe 5.0',
                'price' => 8990000,
                'import_price' => 7300000,
                'line_price' => 10490000,
                'stock' => 600,
                'images' => json_encode(['cpu/u51.jpg', 'cpu/u52.jpg', 'cpu/u53.jpg']),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1851', // Socket mới
                    'cores' => '14 (6P + 8E)',
                    'threads' => 18,
                    'base_clock' => '3.6GHz (P-core) / 2.8GHz (E-core)',
                    'boost_clock' => '5.5GHz (P-core) / 4.2GHz (E-core)',
                    'cache' => '24MB',
                    'tdp' => '125W',
                    'memory' => 'DDR5-6000',
                    'pcie' => 'PCIe 5.0 x16 + 4.0 x4',
                    'npu' => 'Có (AI Boost 10 TOPS)', // NPU tích hợp cho AI
                    'graphics' => 'Intel Arc Xe-LPG (8 EU)',
                    'unlocked' => 'Có'
                ]),
                'brand_id' => 1,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel Core Ultra 7 275K',
                'slug' => 'intel-core-ultra-7-275k-p.2',
                'description' => 'CPU Intel Core Ultra 7 flagship, 16 nhân 22 luồng (8P + 8E), tốc độ 3.8GHz - 5.8GHz, NPU AI 15 TOPS, đồ họa Arc Xe 12EU',
                'price' => 12990000,
                'import_price' => 10500000,
                'line_price' => 14990000,
                'stock' => 350,
                'images' => json_encode(['cpu/u71.jpg', 'cpu/u72.jpg', 'cpu/u73.jpg']),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1851',
                    'cores' => '16 (8P + 8E)',
                    'threads' => 22,
                    'base_clock' => '3.8GHz (P-core) / 3.0GHz (E-core)',
                    'boost_clock' => '5.8GHz (P-core) / 4.4GHz (E-core)',
                    'cache' => '30MB',
                    'tdp' => '150W',
                    'memory' => 'DDR5-6400',
                    'pcie' => 'PCIe 5.0 x20',
                    'npu' => 'Có (AI Boost 15 TOPS)',
                    'graphics' => 'Intel Arc Xe-LPG (12 EU)',
                    'unlocked' => 'Có'
                ]),
                'brand_id' => 1,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel Core Ultra 9 285K',
                'slug' => 'intel-core-ultra-9-285k-p.3',
                'description' => 'CPU Intel Core Ultra 9 đỉnh cao, 24 nhân 32 luồng (8P + 16E), tốc độ 4.0GHz - 6.2GHz, NPU AI 20 TOPS, DDR5-6800, PCIe 5.0',
                'price' => 18990000,
                'import_price' => 15500000,
                'line_price' => 21990000,
                'stock' => 200,
                'images' => json_encode(['cpu/u91.jpg', 'cpu/u92.jpg', 'cpu/u93.jpg']),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1851',
                    'cores' => '24 (8P + 16E)',
                    'threads' => 32,
                    'base_clock' => '4.0GHz (P-core) / 3.2GHz (E-core)',
                    'boost_clock' => '6.2GHz (P-core) / 4.6GHz (E-core)',
                    'cache' => '36MB',
                    'tdp' => '180W',
                    'memory' => 'DDR5-6800',
                    'pcie' => 'PCIe 5.0 x24',
                    'npu' => 'Có (AI Boost 20 TOPS)',
                    'graphics' => 'Intel Arc Xe-LPG (16 EU)',
                    'unlocked' => 'Có'
                ]),
                'brand_id' => 1,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel Core i5 12400F',
                'slug' => 'intel-core-i5-12400f-p.4',
                'description' => 'CPU Intel Core i5 thế hệ 12, 6 nhân 12 luồng, tốc độ 2.5GHz - 4.4GHz, socket LGA1700',
                'price' => 2500000,
                'import_price' => 2000000,
                'line_price' => 2990000,
                'stock' => 1000,
                'images' => json_encode(['cpu/i51.jpg', 'cpu/i52.jpg', 'cpu/i53.jpg']),
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
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel Core i5 12400F tray',
                'slug' => 'intel-core-i5-12400f-tray-p.5',
                'description' => 'CPU Intel Core i5 thế hệ 12, 6 nhân 12 luồng, tốc độ 2.5GHz - 4.4GHz, socket LGA1700',
                'price' => 2500000,
                'import_price' => 2000000,
                'line_price' => 2990000,
                'stock' => 1000,
                'images' => json_encode(['cpu/i51.jpg', 'cpu/i52.jpg', 'cpu/i53.jpg']),
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
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel Core i5-14600KF tray',
                'slug' => 'intel-core-i5-14600kf-tray-p.6',
                'description' => 'CPU Intel Core i5 thế hệ 14, 14 nhân 20 luồng (6P + 8E), tốc độ 3.5GHz - 5.3GHz, hỗ trợ DDR5/DDR4, không tích hợp đồ họa',
                'price' => 7490000,
                'import_price' => 6200000,
                'line_price' => 8490000,
                'stock' => 450,
                'images' => json_encode(['cpu/i5x.jpg']),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1700',
                    'cores' => '14 (6P + 8E)',
                    'threads' => 20,
                    'base_clock' => '3.5GHz (P-core) / 2.6GHz (E-core)',
                    'boost_clock' => '5.3GHz (P-core) / 4.0GHz (E-core)',
                    'cache' => '24MB Intel Smart Cache',
                    'tdp' => '125W (PL2 = 181W)',
                    'memory_support' => 'DDR5-5600 / DDR4-3200',
                    'pcie_version' => 'PCIe 5.0 & 4.0',
                    'graphics' => 'Không (F-series)',
                    'unlocked' => 'Có (K-series)'
                ]),
                'brand_id' => 1,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel Core i7 13700K',
                'slug' => 'intel-core-i7-13700k-p.7',
                'description' => 'CPU Intel Core i7 thế hệ 13, 16 nhân 24 luồng, tốc độ 3.4GHz - 5.4GHz, socket LGA1700',
                'price' => 8990000,
                'import_price' => 7500000,
                'line_price' => 9990000,
                'stock' => 500,
                'images' => json_encode(['cpu/i71.jpg', 'cpu/i72.jpg', 'cpu/i73.jpg']),
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
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Intel Core i9 14900K',
                'slug' => 'intel-core-i9-14900k-p.8',
                'description' => 'CPU Intel Core i9 thế hệ 14 flagship, 24 nhân 32 luồng, tốc độ 3.0GHz - 5.8GHz',
                'price' => 12990000,
                'import_price' => 11000000,
                'line_price' => 14990000,
                'stock' => 200,
                'images' => json_encode(['cpu/i91.webp', 'cpu/i92.webp']),
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
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AMD Ryzen 5 9600X',
                'slug' => 'amd-ryzen-5-9600x-p.9',
                'description' => 'CPU AMD Ryzen 5 thế hệ 9000, 6 nhân 12 luồng, tốc độ 4.3GHz - 5.4GHz',
                'price' => 6490000,
                'import_price' => 5500000,
                'line_price' => 7490000,
                'stock' => 800,
                'images' => json_encode(['cpu/r51.jpg', 'cpu/r52.jpg', 'cpu/r53.jpg', 'cpu/r54.jpg']),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'AM5',
                    'cores' => 6,
                    'threads' => 12,
                    'base_clock' => '4.3GHz',
                    'boost_clock' => '5.4GHz',
                    'cache' => '38MB',
                    'tdp' => '105W'
                ]),
                'brand_id' => 2,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AMD Ryzen 7 9700X',
                'slug' => 'amd-ryzen-7-9700x-p.10',
                'description' => 'CPU AMD Ryzen 7 thế hệ 9000, 8 nhân 16 luồng, tốc độ 4.5GHz - 5.5GHz',
                'price' => 8990000,
                'import_price' => 7500000,
                'line_price' => 9990000,
                'stock' => 500,
                'images' => json_encode(['cpu/r71.jpg', 'cpu/r72.jpg', 'cpu/r73.jpg', 'cpu/r74.jpg']),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'AM5',
                    'cores' => 8,
                    'threads' => 16,
                    'base_clock' => '4.5GHz',
                    'boost_clock' => '5.5GHz',
                    'cache' => '40MB',
                    'tdp' => '120W'
                ]),
                'brand_id' => 2,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AMD Ryzen 9 9900X',
                'slug' => 'amd-ryzen-9-9900x-p.11',
                'description' => 'CPU AMD Ryzen 9 thế hệ 9000 flagship, 12 nhân 24 luồng, tốc độ 4.8GHz - 5.8GHz',
                'price' => 12990000,
                'import_price' => 11000000,
                'line_price' => 14990000,
                'stock' => 200,
                'images' => json_encode(['cpu/r91.jpg', 'cpu/r92.jpg', 'cpu/r93.jpg', 'cpu/r94.jpg']),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'AM5',
                    'cores' => 12,
                    'threads' => 24,
                    'base_clock' => '4.8GHz',
                    'boost_clock' => '5.8GHz',
                    'cache' => '64MB',
                    'tdp' => '170W'
                ]),
                'brand_id' => 2,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AMD Ryzen 7 9800X3D',
                'slug' => 'amd-ryzen-7-9800x3d-p.12',
                'description' => 'CPU AMD Ryzen 7 9800X3D thế hệ 9000 với công nghệ 3D V-Cache, 8 nhân 16 luồng, tốc độ 4.2GHz - 5.4GHz, tối ưu cho game và ứng dụng đòi hỏi bộ nhớ đệm lớn',
                'price' => 11990000,
                'import_price' => 9800000,
                'line_price' => 13990000,
                'stock' => 350,
                'images' => json_encode(['cpu/r7x1.jpg', 'cpu/r7x2.jpg', 'cpu/r7x3.jpg', 'cpu/r7x4.jpg']),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'AM5',
                    'cores' => 8,
                    'threads' => 16,
                    'base_clock' => '4.2GHz',
                    'boost_clock' => '5.4GHz',
                    'cache' => '96MB (L2+L3+3D V-Cache)',
                    'tdp' => '120W',
                    'memory_support' => 'DDR5-5200',
                    'pcie_version' => 'PCIe 5.0',
                    '3d_vcache' => 'Yes' // Công nghệ đặc biệt
                ]),
                'brand_id' => 2, // AMD
                'category_id' => 3, // CPU
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AMD Ryzen 9 9950X3D',
                'slug' => 'amd-ryzen-9-9950x3d-p.13',
                'description' => 'CPU AMD Ryzen 9 flagship thế hệ 9000 với 3D V-Cache, 16 nhân 32 luồng, tốc độ 4.3GHz - 5.7GHz, bộ nhớ đệm 192MB, tối ưu cho gaming 8K và render 3D',
                'price' => 18990000,
                'import_price' => 15500000,
                'line_price' => 21990000,
                'stock' => 150,
                'images' => json_encode(['cpu/r9x1.jpg', 'cpu/r9x2.jpg', 'cpu/r9x3.jpg', 'cpu/r9x4.jpg']),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'AM5',
                    'cores' => 16,
                    'threads' => 32,
                    'base_clock' => '4.3GHz',
                    'boost_clock' => '5.7GHz',
                    'cache' => '192MB (L2+L3+3D V-Cache)',
                    'tdp' => '170W',
                    'memory_support' => 'DDR5-5600 (OC 6000+)',
                    'pcie_version' => 'PCIe 5.0',
                    '3d_vcache' => 'Yes',
                    'unlocked' => 'Yes'
                ]),
                'brand_id' => 2,
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASUS PRIME A620M-K',
                'slug' => 'asus-prime-a620m-k-p.14',
                'description' => 'Mainboard ASUS PRIME A620M-K, socket AM5, hỗ trợ AMD Ryzen™ 7000/8000/9000 series, 2 khe RAM DDR5, cổng M.2 PCIe 4.0, USB 3.2 Gen2, thiết kế tối giản',
                'price' => 2490000,
                'import_price' => 1950000,
                'line_price' => 2890000,
                'stock' => 700,
                'images' => json_encode(['mainboard/asus-a620m-k.png', 'mainboard/asus-a620m-k-1.png', 'mainboard/asus-a620m-k-2.png', 'mainboard/asus-a620m-k-3.png']),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'AM5',
                    'chipset' => 'AMD A620',
                    'memory' => '2x DDR5 (6400MHz OC), tối đa 64GB',
                    'expansion_slots' => '1x PCIe 4.0 x16, 1x PCIe 3.0 x1',
                    'storage' => '4x SATA 6Gb/s, 1x M.2 (PCIe 4.0 x4)',
                    'usb' => '1x USB 3.2 Gen2 Type-A, 1x USB 3.2 Gen1 Type-C, 6x USB 2.0',
                    'video_outputs' => 'HDMI 2.1/DisplayPort 1.4',
                    'audio' => 'Realtek ALC897 (7.1-channel)',
                    'lan' => 'Realtek 1GbE',
                    'form_factor' => 'Micro-ATX (22.6 x 18.5 cm)',
                    'bios_support' => 'AMD Ryzen 7000/8000G/9000 series',
                    'features' => 'Armored PCIe slot, Q-LED for troubleshooting'
                ]),
                'brand_id' => 3, // ASUS
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASUS TUF Gaming B650-PLUS',
                'slug' => 'asus-tuf-gaming-b650-plus-p.15',
                'description' => 'Mainboard ASUS TUF Gaming B650-PLUS (AM5), hỗ trợ AMD Ryzen™ 7000/8000/9000 series, 4 khe DDR5, PCIe 5.0, 3 cổng M.2 (2x PCIe 4.0 + 1x PCIe 5.0), USB4 40Gbps, WiFi 6, chuẩn quân đội bền bỉ',
                'price' => 4990000,
                'import_price' => 4100000,
                'line_price' => 5690000,
                'stock' => 300,
                'images' => json_encode(['mainboard/asus-b650-tuf.png', 'mainboard/asus-b650-tuf-1.png', 'mainboard/asus-b650-tuf-2.png', 'mainboard/asus-b650-tuf-3.png']),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'AM5',
                    'chipset' => 'AMD B650',
                    'memory' => '4x DDR5 (6400+ MHz OC), tối đa 128GB',
                    'expansion_slots' => '1x PCIe 5.0 x16 (Armored) + 1x PCIe 4.0 x16',
                    'storage' => '4x SATA 6Gb/s, 3x M.2 (1x PCIe 5.0 x4 + 2x PCIe 4.0 x4)',
                    'usb' => '1x USB4 Type-C (40Gbps), 5x USB 3.2 Gen2, 4x USB 2.0',
                    'video_outputs' => 'HDMI 2.1/DisplayPort 1.4',
                    'audio' => 'Realtek ALC1200 (7.1-channel) + DTS:X Ultra',
                    'network' => 'Realtek 2.5GbE + Intel WiFi 6 (AX200)',
                    'form_factor' => 'ATX (30.5 x 24.4 cm)',
                    'cooling' => 'VRM heatsink + M.2 heatsink + Fan Xpert 4',
                    'features' => 'Military-grade TUF components, BIOS FlashBack, Aura Sync RGB'
                ]),
                'brand_id' => 3, // ASUS
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MSI A620M-E',
                'slug' => 'msi-a620m-e-p.16',
                'description' => 'Mainboard MSI A620M-E (AM5), hỗ trợ AMD Ryzen™ 7000/8000/9000 series, 2 khe DDR5, PCIe 4.0, 1 cổng M.2 PCIe 4.0, USB 3.2 Gen1, thiết kế Micro-ATX tiết kiệm không gian',
                'price' => 2890000,
                'import_price' => 2400000,
                'line_price' => 3290000,
                'stock' => 150,
                'images' => json_encode(['mainboard/msi-a620m-e.png', 'mainboard/msi-a620m-e-1.png', 'mainboard/msi-a620m-e-2.png', 'mainboard/msi-a620m-e-3.png']),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'AM5',
                    'chipset' => 'AMD A620',
                    'memory' => '2x DDR5 (5600 MHz), tối đa 64GB',
                    'expansion_slots' => '1x PCIe 4.0 x16',
                    'storage' => '4x SATA 6Gb/s, 1x M.2 PCIe 4.0 x4',
                    'usb' => '1x USB 3.2 Gen2 Type-C, 4x USB 3.2 Gen1, 2x USB 2.0',
                    'video_outputs' => 'HDMI 2.1/DisplayPort 1.4',
                    'audio' => 'Realtek ALC897 (7.1-channel)',
                    'network' => 'Realtek 1GbE',
                    'form_factor' => 'Micro-ATX (24.4 x 24.4 cm)',
                    'cooling' => 'VRM heatsink cơ bản',
                    'features' => 'Tương thích AMD EXPO, BIOS Flash, MSI Center'
                ]),
                'brand_id' => 4, // MSI
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASUS ROG Strix B850 BTFi WiFi',
                'slug' => 'asus-rog-strix-b850-btfi-wifi-p.17',
                'description' => 'Mainboard ASUS ROG Strix B850 BTFi WiFi (AM5), hỗ trợ AMD Ryzen™ 7000/8000/9000 series, công nghệ BTF (Back-to-Frame) giấu dây cáp, 4 khe DDR5 (7200+ MHz OC), PCIe 5.0, 3 cổng M.2 (1x PCIe 5.0 + 2x PCIe 4.0), WiFi 6E, USB4 40Gbps, đèn Aura Sync RGB',
                'price' => 6990000,
                'import_price' => 5800000,
                'line_price' => 7990000,
                'stock' => 200,
                'images' => json_encode(['mainboard/asus-b850-btf-wifi.png', 'mainboard/asus-b850-btf-wifi-1.png', 'mainboard/asus-b850-btf-wifi-2.png', 'mainboard/asus-b850-btf-wifi-3.png']),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'AM5',
                    'chipset' => 'AMD B850',
                    'memory' => '4x DDR5 (7200+ MHz OC), tối đa 192GB',
                    'expansion_slots' => '1x PCIe 5.0 x16 (BTF Hidden Connector) + 1x PCIe 4.0 x4',
                    'storage' => '4x SATA 6Gb/s, 3x M.2 (1x PCIe 5.0 x4 + 2x PCIe 4.0 x4)',
                    'usb' => '1x USB4 Type-C (40Gbps), 6x USB 3.2 Gen2 (2x Type-C), 4x USB 2.0',
                    'video_outputs' => 'HDMI 2.1 + DisplayPort 2.1',
                    'audio' => 'ROG SupremeFX ALC4080 (7.1-channel) + ESS Sabre DAC',
                    'network' => 'Intel 2.5GbE + WiFi 6E (BT 5.3)',
                    'form_factor' => 'ATX (30.5 x 24.4 cm) - BTF Edition',
                    'cooling' => 'VRM 16+2 phase heatsink, M.2 heatsink, FAN Xpert 4',
                    'features' => 'Công nghệ BTF (giấu dây cáp), PCIe Slot Q-Release, BIOS FlashBack, Aura Sync RGB, AI Overclocking'
                ]),
                'brand_id' => 3, // ASUS
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MSI MAG B850M Mortar WiFi',
                'slug' => 'msi-mag-b850m-mortar-wifi-p.18',
                'description' => 'Mainboard MSI MAG B850M Mortar WiFi (AM5), hỗ trợ AMD Ryzen™ 7000/8000/9000 series, 4 khe DDR5 (7200+ MHz OC), PCIe 5.0, 3 cổng M.2 (1x PCIe 5.0 + 2x PCIe 4.0), WiFi 6E, USB4 40Gbps, thiết kế quân đội bền bỉ, đèn Mystic Light RGB',
                'price' => 5890000,
                'import_price' => 4900000,
                'line_price' => 6590000,
                'stock' => 180,
                'images' => json_encode(['mainboard/msi-b850m-motar.png', 'mainboard/msi-b850m-motar-1.png', 'mainboard/msi-b850m-motar-2.png', 'mainboard/msi-b850m-motar-3.png', 'mainboard/msi-b850m-motar-4.png']),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'AM5',
                    'chipset' => 'AMD B850',
                    'memory' => '4x DDR5 (7200+ MHz OC), tối đa 192GB',
                    'expansion_slots' => '1x PCIe 5.0 x16 (Armored) + 1x PCIe 4.0 x4',
                    'storage' => '4x SATA 6Gb/s, 3x M.2 (1x PCIe 5.0 x4 + 2x PCIe 4.0 x4)',
                    'usb' => '1x USB4 Type-C (40Gbps), 6x USB 3.2 Gen2 (2x Type-C), 4x USB 2.0',
                    'video_outputs' => 'HDMI 2.1 + DisplayPort 2.1',
                    'audio' => 'Realtek ALC4080 (7.1-channel) + ESS Audio DAC',
                    'network' => 'Realtek 2.5GbE + WiFi 6E (Intel AX210)',
                    'form_factor' => 'Micro-ATX (24.4 x 24.4 cm)',
                    'cooling' => 'VRM 12+2+1 phase (DrMOS), M.2 Shield Frozr, Extended Heatsink',
                    'features' => 'Military-grade components, Mystic Light RGB, BIOS Flashback, PCIe Steel Armor'
                ]),
                'brand_id' => 4, // MSI
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASUS ROG Strix X870-E Gaming WiFi 7',
                'slug' => 'asus-rog-strix-x870-e-gaming-wifi7-p.19',
                'description' => 'Mainboard ASUS ROG Strix X870-E Gaming WiFi 7 (AM5), hỗ trợ AMD Ryzen™ 9000/8000/7000 series, 4 khe DDR5 (8000+ MHz OC), PCIe 5.0, 4 cổng M.2 (2x PCIe 5.0 + 2x PCIe 4.0), WiFi 7, USB4 40Gbps x2, AI Overclocking, đèn Aura Sync RGB',
                'price' => 8990000,
                'import_price' => 7500000,
                'line_price' => 9990000,
                'stock' => 120,
                'images' => json_encode([
                    'mainboard/asus-x870-max-gaming.png',
                    'mainboard/asus-x870-max-gaming-1.png',
                    'mainboard/asus-x870-max-gaming-2.png',
                    'mainboard/asus-x870-max-gaming-3.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'AM5',
                    'chipset' => 'AMD X870',
                    'memory' => '4x DDR5 (8000+ MHz OC), tối đa 256GB',
                    'expansion_slots' => '1x PCIe 5.0 x16 (SafeSlot) + 1x PCIe 4.0 x4',
                    'storage' => '6x SATA 6Gb/s, 4x M.2 (2x PCIe 5.0 x4 + 2x PCIe 4.0 x4)',
                    'usb' => '2x USB4 Type-C (40Gbps), 8x USB 3.2 Gen2 (3x Type-C), 4x USB 2.0',
                    'video_outputs' => 'HDMI 2.1 + DisplayPort 2.1',
                    'audio' => 'ROG SupremeFX ALC4082 (7.1-channel) + ESS ES9218 Quad DAC',
                    'network' => 'Intel 2.5GbE + WiFi 7 (BE200) + Bluetooth 5.4',
                    'form_factor' => 'ATX (30.5 x 24.4 cm)',
                    'cooling' => 'VRM 18+2 phase (110A), M.2 heatsink active fan, ROG Water Cooling Zone',
                    'features' => 'AI Overclocking, AI Cooling II, USB4® x2, BIOS FlashBack, Aura Sync RGB, PCIe Q-Release'
                ]),
                'brand_id' => 3, // ASUS
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASUS ROG Strix X870-I Gaming WiFi 7',
                'slug' => 'asus-rog-strix-x870-i-gaming-wifi7-p.20',
                'description' => 'Mainboard Mini-ITX ASUS ROG Strix X870-I (AM5), hỗ trợ AMD Ryzen™ 9000/8000/7000 series, 2 khe DDR5 (8000+ MHz OC), PCIe 5.0 x16, 3 cổng M.2 (1x PCIe 5.0 + 2x PCIe 4.0), WiFi 7, USB4 40Gbps, AI Overclocking, đèn Aura Sync RGB - Thiết kế tối ưu cho PC SFF',
                'price' => 8490000,
                'import_price' => 7200000,
                'line_price' => 9490000,
                'stock' => 80,
                'images' => json_encode([
                    'mainboard/asus-x870i.png',
                    'mainboard/asus-x870i-1.png',
                    'mainboard/asus-x870i-2.png',
                    'mainboard/asus-x870i-3.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'AM5',
                    'chipset' => 'AMD X870',
                    'memory' => '2x DDR5 (8000+ MHz OC), tối đa 96GB',
                    'expansion_slots' => '1x PCIe 5.0 x16 (SafeSlot)',
                    'storage' => '3x M.2 (1x PCIe 5.0 x4 + 2x PCIe 4.0 x4) + 4x SATA 6Gb/s (via adapter)',
                    'usb' => '1x USB4 Type-C (40Gbps), 6x USB 3.2 Gen2 (2x Type-C), 2x USB 2.0',
                    'video_outputs' => 'HDMI 2.1 + DisplayPort 2.1',
                    'audio' => 'ROG SupremeFX ALC4080 (7.1-channel) + ESS ES9218 Quad DAC',
                    'network' => 'Intel 2.5GbE + WiFi 7 (BE200) + Bluetooth 5.4',
                    'form_factor' => 'Mini-ITX (17.0 x 17.0 cm)',
                    'cooling' => 'VRM 10+2 phase (105A), M.2 heatsink stacked design',
                    'features' => 'AI Overclocking, PCIe Slot Q-Release, BIOS FlashBack, Aura Sync RGB, SFF-optimized layout'
                ]),
                'brand_id' => 3, // ASUS
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MSI MPG X870E Carbon WiFi',
                'slug' => 'msi-mpg-x870e-carbon-wifi-p.21',
                'description' => 'Mainboard MSI MPG X870E Carbon WiFi (AM5), hỗ trợ AMD Ryzen™ 9000/8000/7000 series, 4 khe DDR5 (8200+ MHz OC), PCIe 5.0 x16 + PCIe 5.0 M.2, WiFi 7, USB4 40Gbps, công nghệ Carbon Fiber Armor, đèn Mystic Light RGB',
                'price' => 8790000,
                'import_price' => 7300000,
                'line_price' => 9790000,
                'stock' => 90,
                'images' => json_encode([
                    'mainboard/msi-x870e-carbon.png',
                    'mainboard/msi-x870e-carbon-1.png',
                    'mainboard/msi-x870e-carbon-2.png',
                    'mainboard/msi-x870e-carbon-3.png',
                    'mainboard/msi-x870e-carbon-4.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'AM5',
                    'chipset' => 'AMD X870E',
                    'memory' => '4x DDR5 (8200+ MHz OC), tối đa 256GB',
                    'expansion_slots' => '1x PCIe 5.0 x16 (Steel Armor) + 1x PCIe 4.0 x4',
                    'storage' => '6x SATA 6Gb/s, 4x M.2 (2x PCIe 5.0 x4 + 2x PCIe 4.0 x4)',
                    'usb' => '2x USB4 Type-C (40Gbps), 8x USB 3.2 Gen2x2 (20Gbps), 4x USB 2.0',
                    'video_outputs' => 'HDMI 2.1 + DisplayPort 2.1',
                    'audio' => 'Realtek ALC4082 + ESS ES9218 Quad DAC (7.1-channel, SNR 130dB)',
                    'network' => 'Realtek Dragon 5GbE + WiFi 7 (BE200) + Bluetooth 5.4',
                    'form_factor' => 'ATX (30.5 x 24.4 cm)',
                    'cooling' => 'Extended VRM Heatsink (16+2+1 phase 105A), M.2 Shield Frozr, FROZR AI Cooling',
                    'features' => 'Carbon Fiber PCB Armor, Lightning Gen5 PCIe & M.2, Mystic Light Mirror, BIOS Flash Button'
                ]),
                'brand_id' => 4, // MSI
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASRock Z890 Taichi Aqua',
                'slug' => 'asrock-z890-taichi-aqua-p.22',
                'description' => 'Mainboard ASRock Z890 Taichi Aqua (LGA1851), hỗ trợ Intel Core™ thế hệ 15/14, 4 khe DDR5 (8600+ MHz OC), PCIe 5.0 x16 + PCIe 5.0 M.2, WiFi 7, Thunderbolt™ 4, tích hợp tản nhiệt chất lỏng Aqua, đèn RGB Polychrome SYNC',
                'price' => 12990000,
                'import_price' => 11000000,
                'line_price' => 14990000,
                'stock' => 50,
                'images' => json_encode([
                    'mainboard/asrock-z890-taichi-aqua.png',
                    'mainboard/asrock-z890-taichi-aqua-1.png',
                    'mainboard/asrock-z890-taichi-aqua-2.png',
                    'mainboard/asrock-z890-taichi-aqua-3.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1851',
                    'chipset' => 'Intel Z890',
                    'memory' => '4x DDR5 (8600+ MHz OC), tối đa 256GB',
                    'expansion_slots' => '1x PCIe 5.0 x16 (20+4 phase) + 2x PCIe 5.0 x4',
                    'storage' => '8x SATA 6Gb/s, 5x M.2 (3x PCIe 5.0 x4 + 2x PCIe 4.0 x4)',
                    'usb' => '2x Thunderbolt™ 4 (USB-C 40Gbps), 10x USB 3.2 Gen2x2 (20Gbps), 4x USB 2.0',
                    'video_outputs' => '2x HDMI 2.1 + 2x DisplayPort 2.1',
                    'audio' => 'Realtek ALC4082 + ESS ES9219 Quad DAC (7.1-channel, SNR 135dB)',
                    'network' => 'Killer E3100G 10GbE + WiFi 7 (BE200) + Bluetooth 5.4',
                    'form_factor' => 'E-ATX (30.5 x 27.7 cm)',
                    'cooling' => 'Integrated Liquid Cooling (VRM/M.2), 20+4 phase VRM (110A), Water Cooling Zone',
                    'features' => 'Aqua Cooling System, Taichi Gear Design, Killer DoubleShot™ Pro, BIOS FlashBack, Polychrome SYNC'
                ]),
                'brand_id' => 5, // ASRock
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASUS ProArt Z890 Creator WiFi',
                'slug' => 'asus-proart-z890-creator-wifi-p.23',
                'description' => 'Mainboard ASUS ProArt Z890 Creator WiFi (LGA1851), hỗ trợ Intel Core™ thế hệ 15/14, 4 khe DDR5 (8400+ MHz OC), PCIe 5.0 x16, 4 cổng M.2 (2x PCIe 5.0 + 2x PCIe 4.0), WiFi 7, Thunderbolt™ 4, 10GbE LAN, tối ưu hóa cho workstation sáng tạo',
                'price' => 11990000,
                'import_price' => 9900000,
                'line_price' => 13490000,
                'stock' => 65,
                'images' => json_encode([
                    'mainboard/asus-z890-proart-creator-wifi.png',
                    'mainboard/asus-z890-proart-creator-wifi-1.png',
                    'mainboard/asus-z890-proart-creator-wifi-2.png',
                    'mainboard/asus-z890-proart-creator-wifi-3.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1851',
                    'chipset' => 'Intel Z890',
                    'memory' => '4x DDR5 (8400+ MHz OC), tối đa 256GB',
                    'expansion_slots' => '1x PCIe 5.0 x16 + 1x PCIe 4.0 x4',
                    'storage' => '6x SATA 6Gb/s, 4x M.2 (2x PCIe 5.0 x4 + 2x PCIe 4.0 x4)',
                    'usb' => '2x Thunderbolt™ 4 (USB-C 40Gbps), 8x USB 3.2 Gen2x2 (20Gbps), 4x USB 2.0',
                    'video_outputs' => '2x HDMI 2.1 + 2x DisplayPort 2.1 (via Thunderbolt)',
                    'audio' => 'Realtek ALC1220P (7.1-channel) + ESS ES9023P DAC (SNR 124dB)',
                    'network' => 'Marvell AQtion 10GbE + WiFi 7 (BE200) + Bluetooth 5.4',
                    'form_factor' => 'ATX (30.5 x 24.4 cm)',
                    'cooling' => 'VRM 16+1+2 phase (90A), M.2 heatsink, ProArt Cooling Hub',
                    'features' => 'Creator Mode BIOS, Dual Thunderbolt™ 4, 10GbE LAN, PCIe Slot Q-Release, ASUS Control Center'
                ]),
                'brand_id' => 3, // ASUS
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASRock Z890M Riptide WiFi',
                'slug' => 'asrock-z890m-riptide-wifi-p.24',
                'description' => 'Mainboard ASRock Z890M Riptide WiFi (LGA1851), hỗ trợ Intel Core™ thế hệ 15/14, 4 khe DDR5 (8000+ MHz OC), PCIe 5.0 x16, 3 cổng M.2 (1x PCIe 5.0 + 2x PCIe 4.0), WiFi 6E, 2.5GbE LAN, thiết kế Micro-ATX tối ưu không gian',
                'price' => 6590000,
                'import_price' => 5500000,
                'line_price' => 7290000,
                'stock' => 110,
                'images' => json_encode([
                    'mainboard/z890m-riptide-wifi.png',
                    'mainboard/z890m-riptide-wifi-1.png',
                    'mainboard/z890m-riptide-wifi-2.png',
                    'mainboard/z890m-riptide-wifi-3.png'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1851',
                    'chipset' => 'Intel Z890',
                    'memory' => '4x DDR5 (8000+ MHz OC), tối đa 192GB',
                    'expansion_slots' => '1x PCIe 5.0 x16 + 1x PCIe 4.0 x4',
                    'storage' => '4x SATA 6Gb/s, 3x M.2 (1x PCIe 5.0 x4 + 2x PCIe 4.0 x4)',
                    'usb' => '1x USB 3.2 Gen2x2 Type-C (20Gbps), 6x USB 3.2 Gen2 (10Gbps), 4x USB 2.0',
                    'video_outputs' => '1x HDMI 2.1 + 1x DisplayPort 1.4',
                    'audio' => 'Realtek ALC897 (7.1-channel)',
                    'network' => 'Realtek 2.5GbE + WiFi 6E (AX210) + Bluetooth 5.3',
                    'form_factor' => 'Micro-ATX (24.4 x 24.4 cm)',
                    'cooling' => '8+1+1 phase VRM (60A), M.2 heatsink, Riptide Cooling Armor',
                    'features' => 'ASRock Steel Slot, BIOS FlashBack, Polychrome RGB, Front USB-C header'
                ]),
                'brand_id' => 5, // ASRock
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MSI MEG Z890 GODLIKE',
                'slug' => 'msi-meg-z890-godlike-p.25',
                'description' => 'Flagship motherboard MSI MEG Z890 GODLIKE (LGA1851), hỗ trợ Intel Core™ thế hệ 15/14, 4 khe DDR5 (9000+ MHz OC), 3x PCIe 5.0 x16, 5 cổng M.2 (3x PCIe 5.0 + 2x PCIe 4.0), WiFi 7, Thunderbolt™ 4, 10GbE+2.5GbE LAN, màn hình OLED tích hợp, công nghệ Liquid Cooling cho VRM',
                'price' => 18990000,
                'import_price' => 15500000,
                'line_price' => 20990000,
                'stock' => 25,
                'images' => json_encode([
                    'mainboard/msi-z890-god-like.png',
                    'mainboard/msi-z890-god-like-1.png',
                    'mainboard/msi-z890-god-like-2.png',
                    'mainboard/msi-z890-god-like-3.png',
                    'mainboard/msi-z890-god-like-4.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1851',
                    'chipset' => 'Intel Z890',
                    'memory' => '4x DDR5 (9000+ MHz OC), tối đa 256GB',
                    'expansion_slots' => '3x PCIe 5.0 x16 (x16/x8/x8) + 1x PCIe 4.0 x4',
                    'storage' => '8x SATA 6Gb/s, 5x M.2 (3x PCIe 5.0 x4 + 2x PCIe 4.0 x4)',
                    'usb' => '2x Thunderbolt™ 4 (40Gbps), 12x USB 3.2 Gen2x2 (20Gbps), 6x USB 2.0',
                    'video_outputs' => '2x HDMI 2.1 + 2x DisplayPort 2.1 (via Thunderbolt)',
                    'audio' => 'ESS ES9039Q2M DAC (7.1-channel, SNR 140dB) + Audio Boost 5',
                    'network' => 'Aquantia 10GbE + Intel 2.5GbE + WiFi 7 (BE200) + Bluetooth 5.4',
                    'form_factor' => 'E-ATX (30.5 x 27.7 cm)',
                    'cooling' => 'Liquid Cooling VRM (26+2+1 phase 105A), M.2 XPANDER-Z Gen5, FROZR AI Cooling',
                    'features' => 'Dynamic Dashboard OLED, LN2 Mode, PCIe Gen5 Steel Armor, M.2 XPANDER-Z, WiFi 7 Quad Antenna'
                ]),
                'brand_id' => 4, // MSI
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MSI B860M Mortar WiFi',
                'slug' => 'msi-b860m-mortar-wifi-p.26',
                'description' => 'Bo mạch chủ tầm trung MSI B860M Mortar WiFi (LGA1851), hỗ trợ Intel Core™ thế hệ 15/14, 4 khe DDR5 (7000+ MHz OC), 1x PCIe 5.0 x16, 3 cổng M.2 (1x PCIe 5.0 + 2x PCIe 4.0), WiFi 6E, 2.5GbE LAN, thiết kế quân đội bền bỉ, tản nhiệt VRM mở rộng',
                'price' => 5490000,
                'import_price' => 4200000,
                'line_price' => 5990000,
                'stock' => 50,
                'images' => json_encode([
                    'mainboard/msi-b860m-motar-wifi.png',
                    'mainboard/msi-b860m-motar-wifi-1.png',
                    'mainboard/msi-b860m-motar-wifi-2.png',
                    'mainboard/msi-b860m-motar-wifi-3.png',
                    'mainboard/msi-b860m-motar-wifi-4.png'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1851',
                    'chipset' => 'Intel B860',
                    'memory' => '4x DDR5 (7000+ MHz OC), tối đa 128GB',
                    'expansion_slots' => '1x PCIe 5.0 x16 + 1x PCIe 4.0 x4',
                    'storage' => '6x SATA 6Gb/s, 3x M.2 (1x PCIe 5.0 x4 + 2x PCIe 4.0 x4)',
                    'usb' => '1x USB 3.2 Gen2x2 (20Gbps), 6x USB 3.2 Gen2 (10Gbps), 4x USB 2.0',
                    'video_outputs' => '1x HDMI 2.1 + 1x DisplayPort 1.4',
                    'audio' => 'Realtek ALC897 (7.1-channel, SNR 110dB)',
                    'network' => 'Realtek 2.5GbE + WiFi 6E (AX211) + Bluetooth 5.3',
                    'form_factor' => 'Micro-ATX (24.4 x 24.4 cm)',
                    'cooling' => 'Extended VRM Heatsink (12+1+1 phase 60A), M.2 Shield FROZR',
                    'features' => 'Military-grade Components, PCIe Steel Armor, Mystic Light RGB, M.2 Thermal Guard'
                ]),
                'brand_id' => 4, // MSI
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASRock B860I WiFi',
                'slug' => 'asrock-b860i-wifi-p.27',
                'description' => 'Bo mạch chủ Mini-ITX ASRock B860I WiFi (LGA1851), hỗ trợ Intel Core™ thế hệ 15/14, 2 khe DDR5 (6400+ MHz OC), 1x PCIe 5.0 x16, 2 cổng M.2 (1x PCIe 5.0 + 1x PCIe 4.0), WiFi 6E, 2.5GbE LAN, thiết kế nhỏ gọn, tản nhiệt VRM hiệu quả',
                'price' => 4990000,
                'import_price' => 3800000,
                'line_price' => 5490000,
                'stock' => 30,
                'images' => json_encode([
                   'mainboard/asrock-b860i-wifi.png',
                    'mainboard/asrock-b860i-wifi-1.png',
                    'mainboard/asrock-b860i-wifi-2.png',
                    'mainboard/asrock-b860i-wifi-3.png'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1851',
                    'chipset' => 'Intel B860',
                    'memory' => '2x DDR5 (6400+ MHz OC), tối đa 64GB',
                    'expansion_slots' => '1x PCIe 5.0 x16',
                    'storage' => '4x SATA 6Gb/s, 2x M.2 (1x PCIe 5.0 x4 + 1x PCIe 4.0 x4)',
                    'usb' => '1x USB 3.2 Gen2x2 (20Gbps), 4x USB 3.2 Gen2 (10Gbps), 2x USB 2.0',
                    'video_outputs' => '1x HDMI 2.1 + 1x DisplayPort 1.4',
                    'audio' => 'Realtek ALC897 (7.1-channel, SNR 110dB)',
                    'network' => 'Realtek 2.5GbE + WiFi 6E (AX210) + Bluetooth 5.2',
                    'form_factor' => 'Mini-ITX (17.0 x 17.0 cm)',
                    'cooling' => 'VRM Heatsink (8+1+1 phase 50A), M.2 Shield',
                    'features' => 'Compact Design, PCIe Steel Slot, SATA & USB 3.2 Gen2, M.2 Thermal Guard'
                ]),
                'brand_id' => 5, // ASRock
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASRock B860M Lightning WiFi',
                'slug' => 'asrock-b860m-lightning-wifi-p.28',
                'description' => 'Bo mạch chủ Micro-ATX ASRock B860M Lightning WiFi (LGA1851), hỗ trợ Intel Core™ thế hệ 15/14, 4 khe DDR5 (6200+ MHz OC), 1x PCIe 5.0 x16, 3 cổng M.2 (1x PCIe 5.0 + 2x PCIe 4.0), WiFi 6, 2.5GbE LAN, thiết kế tối ưu cho gaming và đa nhiệm',
                'price' => 4290000,
                'import_price' => 3200000,
                'line_price' => 4790000,
                'stock' => 40,
                'images' => json_encode([
                    'mainboard/asrock-b860m-lightning-wifi.png',
                    'mainboard/asrock-b860m-lightning-wifi-1.png',
                    'mainboard/asrock-b860m-lightning-wifi-2.png',
                    'mainboard/asrock-b860m-lightning-wifi-3.png'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1851',
                    'chipset' => 'Intel B860',
                    'memory' => '4x DDR5 (6200+ MHz OC), tối đa 128GB',
                    'expansion_slots' => '1x PCIe 5.0 x16 + 1x PCIe 3.0 x1',
                    'storage' => '4x SATA 6Gb/s, 3x M.2 (1x PCIe 5.0 x4 + 2x PCIe 4.0 x4)',
                    'usb' => '1x USB 3.2 Gen2x2 (20Gbps), 4x USB 3.2 Gen1 (5Gbps), 2x USB 2.0',
                    'video_outputs' => '1x HDMI 2.1 + 1x DisplayPort 1.4',
                    'audio' => 'Realtek ALC897 (7.1-channel, SNR 110dB)',
                    'network' => 'Realtek 2.5GbE + WiFi 6 (AX200) + Bluetooth 5.1',
                    'form_factor' => 'Micro-ATX (24.4 x 24.4 cm)',
                    'cooling' => 'VRM Heatsink (10+1+1 phase 55A), M.2 Shield',
                    'features' => 'Lightning Gaming Series, PCIe Steel Slot, Polychrome RGB, M.2 Thermal Guard'
                ]),
                'brand_id' => 5, // ASRock
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASUS B860M TUF Gaming WiFi',
                'slug' => 'asus-b860m-tuf-gaming-wifi-p.29',
                'description' => 'Bo mạch chủ Micro-ATX ASUS B860M TUF Gaming WiFi (LGA1851), hỗ trợ Intel Core™ thế hệ 15/14, 4 khe DDR5 (6800+ MHz OC), 1x PCIe 5.0 x16, 3 cổng M.2 (1x PCIe 5.0 + 2x PCIe 4.0), WiFi 6, 2.5GbE LAN, thiết kế TUF Military Grade bền bỉ, tản nhiệt VRM mở rộng',
                'price' => 5290000,
                'import_price' => 4100000,
                'line_price' => 5790000,
                'stock' => 35,
                'images' => json_encode([
                    'mainboard/asus-b860m-tuf-wifi.png',
                    'mainboard/asus-b860m-tuf-wifi-1.png',
                    'mainboard/asus-b860m-tuf-wifi-2.png',
                    'mainboard/asus-b860m-tuf-wifi-3.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1851',
                    'chipset' => 'Intel B860',
                    'memory' => '4x DDR5 (6800+ MHz OC), tối đa 128GB',
                    'expansion_slots' => '1x PCIe 5.0 x16 + 1x PCIe 4.0 x4',
                    'storage' => '4x SATA 6Gb/s, 3x M.2 (1x PCIe 5.0 x4 + 2x PCIe 4.0 x4)',
                    'usb' => '1x USB 3.2 Gen2x2 (20Gbps), 6x USB 3.2 Gen1 (5Gbps), 2x USB 2.0',
                    'video_outputs' => '1x HDMI 2.1 + 1x DisplayPort 1.4',
                    'audio' => 'Realtek ALC897 (7.1-channel, SNR 110dB) + DTS Audio Customization',
                    'network' => 'Realtek 2.5GbE + WiFi 6 (AX201) + Bluetooth 5.2',
                    'form_factor' => 'Micro-ATX (24.4 x 24.4 cm)',
                    'cooling' => 'VRM Heatsink (12+1+1 phase 60A), M.2 Heatsink, Hybrid Fan Headers',
                    'features' => 'TUF Gaming Protection (Military-grade Components), PCIe Steel Slot, Aura Sync RGB, M.2 Q-Latch'
                ]),
                'brand_id' => 1, // ASUS
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASRock Z790 Taichi',
                'slug' => 'asrock-z790-taichi-p.30',
                'description' => 'Flagship motherboard ASRock Z790 Taichi (LGA1700), hỗ trợ Intel Core™ thế hệ 12/13/14, 4 khe DDR5 (7200+ MHz OC), 3x PCIe 5.0 x16, 5 cổng M.2 (3x PCIe 4.0 + 2x PCIe 3.0), WiFi 6E, 10GbE + 2.5GbE LAN, thiết kế Taichi cao cấp, đèn RGB Addressable, VRM 24+1+2 phase 105A',
                'price' => 12990000,
                'import_price' => 9900000,
                'line_price' => 13990000,
                'stock' => 15,
                'images' => json_encode([
                    'mainboard/asrock-790-taichi-wifi.png',
                    'mainboard/asrock-790-taichi-wifi-1.png',
                    'mainboard/asrock-790-taichi-wifi-2.png',
                    'mainboard/asrock-790-taichi-wifi-3.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1700',
                    'chipset' => 'Intel Z790',
                    'memory' => '4x DDR5 (7200+ MHz OC), tối đa 128GB',
                    'expansion_slots' => '3x PCIe 5.0 x16 (x16/x8/x8) + 1x PCIe 4.0 x4',
                    'storage' => '8x SATA 6Gb/s, 5x M.2 (3x PCIe 4.0 x4 + 2x PCIe 3.0 x4)',
                    'usb' => '2x Thunderbolt™ 4 (Type-C, 40Gbps), 10x USB 3.2 Gen2 (10Gbps), 4x USB 2.0',
                    'video_outputs' => '1x HDMI 2.1 + 1x DisplayPort 1.4',
                    'audio' => 'Realtek ALC4082 (7.1-channel, SNR 120dB) + ESS SABRE9218 DAC',
                    'network' => 'Aquantia 10GbE + Intel 2.5GbE + WiFi 6E (AX210) + Bluetooth 5.2',
                    'form_factor' => 'ATX (30.5 x 24.4 cm)',
                    'cooling' => 'VRM 24+1+2 phase (105A), Active PCH Heatsink, M.2 Aluminum Heatsink',
                    'features' => 'Taichi Gear Design, Polychrome RGB, Killer DoubleShot Pro, BIOS Flashback'
                ]),
                'brand_id' => 5, // ASRock
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASUS ROG Maximus Z790 Hero',
                'slug' => 'asus-rog-maximus-z790-hero-p.31',
                'description' => 'Flagship motherboard ASUS ROG Maximus Z790 Hero (LGA1700), hỗ trợ Intel Core™ thế hệ 13/14, 4 khe DDR5 (7800+ MHz OC), 2x PCIe 5.0 x16, 5 cổng M.2 (4x PCIe 4.0 + 1x PCIe 3.0), WiFi 6E, 10GbE + 2.5GbE LAN, công nghệ AI Overclocking, màn hình OLED ROG AniMe Matrix, VRM 20+1+2 phase 90A',
                'price' => 15990000,
                'import_price' => 12500000,
                'line_price' => 16990000,
                'stock' => 12,
                'images' => json_encode([
                    'mainboard/asus-z790-rog-maximus-hero.png',
                    'mainboard/asus-z790-rog-maximus-hero-1.png',
                    'mainboard/asus-z790-rog-maximus-hero-2.png',
                    'mainboard/asus-z790-rog-maximus-hero-3.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1700',
                    'chipset' => 'Intel Z790',
                    'memory' => '4x DDR5 (7800+ MHz OC), tối đa 128GB',
                    'expansion_slots' => '2x PCIe 5.0 x16 (x16/x8 or x8/x8) + 1x PCIe 4.0 x4',
                    'storage' => '6x SATA 6Gb/s, 5x M.2 (4x PCIe 4.0 x4 + 1x PCIe 3.0 x4)',
                    'usb' => '2x Thunderbolt™ 4 (Type-C, 40Gbps), 12x USB 3.2 Gen2 (10Gbps), 4x USB 2.0',
                    'video_outputs' => '1x HDMI 2.1 + 1x DisplayPort 1.4',
                    'audio' => 'ROG SupremeFX ALC4082 (7.1-channel, SNR 120dB) + ESS ES9218 Quad DAC',
                    'network' => 'Marvell AQtion 10GbE + Intel 2.5GbE + WiFi 6E (AX211) + Bluetooth 5.3',
                    'form_factor' => 'ATX (30.5 x 24.4 cm)',
                    'cooling' => 'VRM 20+1+2 phase (90A), CrossChill EK III Hybrid Cooling, M.2 heatsinks',
                    'features' => 'ROG AniMe Matrix OLED, AI Overclocking, AI Cooling II, BIOS FlashBack, Aura Sync RGB'
                ]),
                'brand_id' => 1, // ASUS
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MSI MAG Z790 Tomahawk WiFi',
                'slug' => 'msi-mag-z790-tomahawk-wifi-p.32',
                'description' => 'Bo mạch chủ ATX MSI MAG Z790 Tomahawk WiFi (LGA1700), hỗ trợ Intel Core™ thế hệ 12/13/14, 4 khe DDR5 (7200+ MHz OC), 2x PCIe 5.0 x16, 4 cổng M.2 (2x PCIe 4.0 + 2x PCIe 3.0), WiFi 6E, 2.5GbE LAN, công nghệ tản nhiệt Extended Heatsink, VRM 16+1+1 phase 80A, chuẩn quân đội bền bỉ',
                'price' => 7990000,
                'import_price' => 6200000,
                'line_price' => 8490000,
                'stock' => 28,
                'images' => json_encode([
                    'mainboard/msi-z790-toma-d4.png',
                    'mainboard/msi-z790-toma-d4-1.png',
                    'mainboard/msi-z790-toma-d4-2.png',
                    'mainboard/msi-z790-toma-d4-3.png',
                    'mainboard/msi-z790-toma-d4-4.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1700',
                    'chipset' => 'Intel Z790',
                    'memory' => '4x DDR5 (7200+ MHz OC), tối đa 128GB',
                    'expansion_slots' => '2x PCIe 5.0 x16 (x16/x4) + 1x PCIe 3.0 x1',
                    'storage' => '6x SATA 6Gb/s, 4x M.2 (2x PCIe 4.0 x4 + 2x PCIe 3.0 x4)',
                    'usb' => '1x USB 3.2 Gen2x2 Type-C (20Gbps), 7x USB 3.2 Gen2 (10Gbps), 4x USB 2.0',
                    'video_outputs' => '1x HDMI 2.1 + 1x DisplayPort 1.4',
                    'audio' => 'Realtek ALC897 (7.1-channel, SNR 110dB)',
                    'network' => 'Intel 2.5GbE + WiFi 6E (AX211) + Bluetooth 5.3',
                    'form_factor' => 'ATX (30.5 x 24.4 cm)',
                    'cooling' => 'Extended VRM Heatsink (16+1+1 phase 80A), M.2 Shield FROZR',
                    'features' => 'Military-grade Components, PCIe Steel Armor, Mystic Light RGB, Memory Boost'
                ]),
                'brand_id' => 4, // MSI
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASUS Prime B760M-Plus WiFi',
                'slug' => 'asus-prime-b760m-plus-wifi-p.33',
                'description' => 'Bo mạch chủ Micro-ATX ASUS Prime B760M-Plus WiFi (LGA1700), hỗ trợ Intel Core™ thế hệ 12/13/14, 4 khe DDR4 (5333+ MHz OC), 1x PCIe 5.0 x16, 3 cổng M.2 (1x PCIe 4.0 + 2x PCIe 3.0), WiFi 6, 2.5GbE LAN, thiết kế tản nhiệt VRM mở rộng, công nghệ AI Noise Cancellation',
                'price' => 4590000,
                'import_price' => 3500000,
                'line_price' => 4990000,
                'stock' => 45,
                'images' => json_encode([
                    'mainboard/asus-b760m-plus.png',
                    'mainboard/asus-b760m-plus-1.png',
                    'mainboard/asus-b760m-plus-2.png',
                    'mainboard/asus-b760m-plus-3.png'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1700',
                    'chipset' => 'Intel B760',
                    'memory' => '4x DDR4 (5333+ MHz OC), tối đa 128GB',
                    'expansion_slots' => '1x PCIe 5.0 x16 + 1x PCIe 3.0 x1',
                    'storage' => '4x SATA 6Gb/s, 3x M.2 (1x PCIe 4.0 x4 + 2x PCIe 3.0 x4)',
                    'usb' => '1x USB 3.2 Gen2x2 Type-C (20Gbps), 5x USB 3.2 Gen1 (5Gbps), 4x USB 2.0',
                    'video_outputs' => '1x HDMI 2.1 + 1x DisplayPort 1.4',
                    'audio' => 'Realtek ALC897 (7.1-channel, SNR 110dB)',
                    'network' => 'Realtek 2.5GbE + WiFi 6 (AX201) + Bluetooth 5.2',
                    'form_factor' => 'Micro-ATX (24.4 x 24.4 cm)',
                    'cooling' => 'VRM Heatsink (8+1+1 phase 50A), M.2 Heatsink',
                    'features' => 'AI Noise Cancellation, BIOS FlashBack, ASUS OptiMem II, Aura Sync RGB'
                ]),
                'brand_id' => 1, // ASUS
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MSI PRO B760M-G DDR4',
                'slug' => 'msi-pro-b760m-g-ddr4-p.34',
                'description' => 'Bo mạch chủ Micro-ATX MSI PRO B760M-G DDR4 (LGA1700), hỗ trợ Intel Core™ thế hệ 12/13, 2 khe DDR4 (4800MHz), 1x PCIe 4.0 x16, 1 cổng M.2 PCIe 4.0 x4, 2.5GbE LAN, thiết kế tối giản với tản nhiệt cơ bản, phù hợp cho hệ thống văn phòng và giải trí cơ bản',
                'price' => 2890000,
                'import_price' => 2200000,
                'line_price' => 3290000,
                'stock' => 60,
                'images' => json_encode([
                    'mainboard/msi-b760-g.png',
                    'mainboard/msi-b760-g1.png',
                    'mainboard/msi-b760-g2.png',
                    'mainboard/msi-b760-g3.png',
                    'mainboard/msi-b760-g4.png'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'socket' => 'LGA1700',
                    'chipset' => 'Intel B760',
                    'memory' => '2x DDR4 (4800MHz), tối đa 64GB',
                    'expansion_slots' => '1x PCIe 4.0 x16 + 1x PCIe 3.0 x1',
                    'storage' => '4x SATA 6Gb/s, 1x M.2 PCIe 4.0 x4',
                    'usb' => '1x USB 3.2 Gen1 Type-C (5Gbps), 4x USB 3.2 Gen1 (5Gbps), 2x USB 2.0',
                    'video_outputs' => '1x HDMI 1.4 + 1x DisplayPort 1.2',
                    'audio' => 'Realtek ALC897 (7.1-channel)',
                    'network' => 'Realtek 2.5GbE LAN',
                    'form_factor' => 'Micro-ATX (23.6 x 20.9 cm)',
                    'cooling' => 'Basic VRM heatsink (6+1+1 phase)',
                    'features' => 'Core Boost technology, DDR4 Boost, Simplified BIOS'
                ]),
                'brand_id' => 4, // MSI
                'category_id' => 2, // Mainboard
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Corsair Dominator Platinum RGB DDR5 32GB (2x16GB) 6000MHz',
                'slug' => 'corsair-dominator-platinum-rgb-ddr5-32gb-6000mhz-p.35',
                'description' => 'RAM DDR5 Corsair Dominator Platinum RGB 32GB (2x16GB) tốc độ 6000MHz, latency CL36, hỗ trợ Intel XMP 3.0 và AMD EXPO, tản nhiệt nhôm cao cấp, đèn RGB 12 LED/thanh, phù hợp cho gaming và workstation',
                'price' => 5490000,
                'import_price' => 4200000,
                'line_price' => 5990000,
                'stock' => 20,
                'images' => json_encode([
                    'ram/adata-lancer.png',
                    'ram/adata-lancer-1.png',
                    'ram/adata-lancer-2.png',
                    'ram/adata-lancer-3.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'capacity' => '32GB (2x16GB)',
                    'speed' => '6000MHz',
                    'latency' => 'CL36',
                    'voltage' => '1.35V',
                    'cooling' => 'Nhôm nguyên khối + RGB',
                    'compatibility' => 'Intel XMP 3.0 / AMD EXPO'
                ]),
                'brand_id' => 6, // Corsair
                'category_id' => 4, // RAM
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kingston Fury Beast DDR5 16GB (1x16GB) 5200MHz',
                'slug' => 'kingston-fury-beast-ddr5-16gb-5200mhz-p.36',
                'description' => 'RAM DDR5 Kingston Fury Beast 16GB (1x16GB) 5200MHz, CL40, thiết kế tản nhiệt màu đen, plug & play với Intel/AMD, không RGB, phù hợp build PC tiết kiệm',
                'price' => 1890000,
                'import_price' => 1400000,
                'line_price' => 2190000,
                'stock' => 50,
                'images' => json_encode([
                    'ram/adata-lancer.png',
                    'ram/adata-lancer-1.png',
                    'ram/adata-lancer-2.png',
                    'ram/adata-lancer-3.png'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'capacity' => '16GB (1x16GB)',
                    'speed' => '5200MHz',
                    'latency' => 'CL40',
                    'voltage' => '1.25V',
                    'cooling' => 'Nhôm phủ đen',
                    'compatibility' => 'Intel/AMD'
                ]),
                'brand_id' => 7, // Kingston
                'category_id' => 4, // RAM
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'G.Skill Trident Z RGB DDR4 32GB (2x16GB) 3600MHz',
                'slug' => 'gskill-trident-z-rgb-ddr4-32gb-3600mhz-p.37',
                'description' => 'RAM DDR4 G.Skill Trident Z RGB 32GB (2x16GB) 3600MHz, CL18, thiết kế vỏ nhôm RGB sáng đẹp, hỗ trợ XMP 2.0, phù hợp cho gaming và đồ họa',
                'price' => 3290000,
                'import_price' => 2500000,
                'line_price' => 3690000,
                'stock' => 30,
                'images' => json_encode([
                    'ram/adata-lancer.png',
                    'ram/adata-lancer-1.png',
                    'ram/adata-lancer-2.png',
                    'ram/adata-lancer-3.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'capacity' => '32GB (2x16GB)',
                    'speed' => '3600MHz',
                    'latency' => 'CL18',
                    'voltage' => '1.35V',
                    'cooling' => 'Nhôm + RGB 8 LED/thanh',
                    'compatibility' => 'Intel XMP 2.0'
                ]),
                'brand_id' => 8, // G.Skill
                'category_id' => 4, // RAM
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Crucial Ballistix DDR4 16GB (2x8GB) 3200MHz',
                'slug' => 'crucial-ballistix-ddr4-16gb-3200mhz-p.38',
                'description' => 'RAM DDR4 Crucial Ballistix 16GB (2x8GB) 3200MHz, CL16, tản nhiệt nhôm màu đỏ/đen, hiệu năng ổn định, phù hợp cho PC văn phòng và gaming tầm trung',
                'price' => 1490000,
                'import_price' => 1100000,
                'line_price' => 1790000,
                'stock' => 40,
                'images' => json_encode([
                    'ram/ram1.png',
                    'ram/ram2.png',
                    'ram/ram3.png',
                    'ram/ram4.png',
                    'ram/ram5.png'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'capacity' => '16GB (2x8GB)',
                    'speed' => '3200MHz',
                    'latency' => 'CL16',
                    'voltage' => '1.35V',
                    'cooling' => 'Nhôm phủ màu',
                    'compatibility' => 'Intel/AMD'
                ]),
                'brand_id' => 9, // Crucial
                'category_id' => 4, // RAM
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'G.Skill Trident Z5 RGB DDR5 64GB (2x32GB) 6400MHz',
                'slug' => 'gskill-trident-z5-rgb-ddr5-64gb-6400mhz-p.39',
                'description' => 'RAM DDR5 G.Skill Trident Z5 RGB 64GB (2x32GB) 6400MHz, CL32, thiết kế RGB cao cấp, tản nhiệt nhôm kép, hỗ trợ XMP 3.0, dành cho workstation và streaming',
                'price' => 8990000,
                'import_price' => 7200000,
                'line_price' => 9990000,
                'stock' => 10,
                'images' => json_encode([
                    'ram/ram1.png',
                    'ram/ram2.png',
                    'ram/ram3.png',
                    'ram/ram4.png',
                    'ram/ram5.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'capacity' => '64GB (2x32GB)',
                    'speed' => '6400MHz',
                    'latency' => 'CL32',
                    'voltage' => '1.4V',
                    'cooling' => 'Nhôm kép + RGB',
                    'compatibility' => 'Intel XMP 3.0'
                ]),
                'brand_id' => 8, // G.Skill
                'category_id' => 4, // RAM
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASUS Dual GeForce RTX 5060 8GB GDDR6',
                'slug' => 'asus-dual-geforce-rtx-5060-8gb-gddr6-p.40',
                'description' => 'Card màn hình ASUS Dual RTX 5060 8GB GDDR6, thiết kế 2 quạt tản nhiệt, hiệu năng ổn định, phù hợp cho gaming và đồ họa',
                'price' => 8990000,
                'import_price' => 7500000,
                'line_price' => 9500000,
                'stock' => 15,
                'images' => json_encode([
                    'vga/asus-rtx5060-dual.png',
                    'vga/asus-rtx5060-dual-1.png',
                    'vga/asus-rtx5060-dual-2.png',
                    'vga/asus-rtx5060-dual-3.png',
                    'vga/asus-rtx5060-dual-4.png',
                    'vga/asus-rtx5060-dual-5.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'chipset' => 'NVIDIA GeForce RTX 5060',
                    'memory' => '8GB GDDR6',
                    'memory_bus' => '128-bit',
                    'gpu_clock' => 'Boost: 1800 MHz',
                    'outputs' => 'HDMI 2.1, DisplayPort 1.4a (x3)',
                    'power_connector' => '1x 8-pin',
                    'recommended_psu' => '550W',
                    'cooling' => 'Dual-fan',
                    'dimensions' => '232 x 123 x 43 mm'
                ]),
                'brand_id' => 1, // ASUS
                'category_id' => 5, // VGA
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASUS TUF Gaming GeForce RTX 5060 8GB GDDR6',
                'slug' => 'asus-tuf-gaming-rtx-5060-8gb-gddr6-p.41',
                'description' => 'Card màn hình ASUS TUF Gaming RTX 5060 8GB GDDR6, thiết kế bền bỉ với hệ thống làm mát 3 quạt, chế độ 0dB, độ bền quân đội, hiệu năng vượt trội cho game thủ',
                'price' => 9490000,
                'import_price' => 8000000,
                'line_price' => 9990000,
                'stock' => 12,
                'images' => json_encode([
                    'vga/asus-rtx5060-tuf.png',
                    'vga/asus-rtx5060-tuf-1.png',
                    'vga/asus-rtx5060-tuf-2.png',
                    'vga/asus-rtx5060-tuf-3.png',
                    'vga/asus-rtx5060-tuf-4.png',
                    'vga/asus-rtx5060-tuf-5.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'chipset' => 'NVIDIA GeForce RTX 5060',
                    'memory' => '8GB GDDR6',
                    'memory_bus' => '128-bit',
                    'gpu_clock' => 'Boost: 1830 MHz (OC Mode) / 1800 MHz (Gaming Mode)',
                    'outputs' => 'HDMI 2.1, DisplayPort 1.4a (x3)',
                    'power_connector' => '1x 8-pin',
                    'recommended_psu' => '550W',
                    'cooling' => '3x Axial-tech Fan (0dB technology)',
                    'durability' => 'Military-grade capacitors, Auto-Extreme manufacturing',
                    'dimensions' => '245 x 130 x 52 mm',
                    'rgb' => 'Không'
                ]),
                'brand_id' => 1, // ASUS
                'category_id' => 5, // VGA
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASUS Prime GeForce RTX 5070 12GB GDDR6',
                'slug' => 'asus-prime-geforce-rtx-5070-12gb-gddr6-p.42',
                'description' => 'Card màn hình ASUS Prime RTX 5070 12GB GDDR6, thiết kế tối giản với hệ thống làm mát 2 quạt, hiệu năng ổn định, phù hợp cho gaming và đồ họa chuyên nghiệp',
                'price' => 12990000,
                'import_price' => 11000000,
                'line_price' => 13990000,
                'stock' => 10,
                'images' => json_encode([
                    'vga/asus-rtx-5070-prime.png',
                    'vga/asus-rtx-5070-prime-1.png',
                    'vga/asus-rtx-5070-prime-2.png',
                    'vga/asus-rtx-5070-prime-3.png',
                    'vga/asus-rtx-5070-prime-4.png',
                    'vga/asus-rtx-5070-prime-5.png',
                    'vga/asus-rtx-5070-prime-6.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'chipset' => 'NVIDIA GeForce RTX 5070',
                    'memory' => '12GB GDDR6',
                    'memory_bus' => '192-bit',
                    'gpu_clock' => 'Boost: 2100 MHz (OC Mode) / 2070 MHz (Gaming Mode)',
                    'outputs' => 'HDMI 2.1, DisplayPort 1.4a (x3)',
                    'power_connector' => '1x 8-pin + 1x 6-pin',
                    'recommended_psu' => '650W',
                    'cooling' => '2x Axial-tech Fan (0dB technology)',
                    'durability' => 'Auto-Extreme manufacturing, high-quality components',
                    'dimensions' => '240 x 120 x 45 mm',
                    'rgb' => 'Không'
                ]),
                'brand_id' => 1, // ASUS
                'category_id' => 5, // VGA
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASUS ROG Strix GeForce RTX 5070 OC Edition 12GB GDDR6X',
                'slug' => 'asus-rog-strix-rtx-5070-oc-12gb-gddr6x-p.43',
                'description' => 'Card đồ họa flagship ASUS ROG Strix RTX 5070 OC Edition 12GB GDDR6X, hệ thống làm mát 3 quạt Axial-tech, RGB Aura Sync, xung nhịp cao sẵn sàng cho 4K gaming và sáng tạo nội dung',
                'price' => 15990000,
                'import_price' => 13500000,
                'line_price' => 16990000,
                'stock' => 8,
                'images' => json_encode([
                    'vga/asus-rtx-5070-strix.png',
                    'vga/asus-rtx-5070-strix-1.png',
                    'vga/asus-rtx-5070-strix-2.png',
                    'vga/asus-rtx-5070-strix-3.png',
                    'vga/asus-rtx-5070-strix-4.png',
                    'vga/asus-rtx-5070-strix-5.png',
                    'vga/asus-rtx-5070-strix-6.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'chipset' => 'NVIDIA GeForce RTX 5070',
                    'memory' => '12GB GDDR6X',
                    'memory_bus' => '192-bit',
                    'memory_speed' => '21Gbps',
                    'gpu_clock' => 'OC Mode: 2535 MHz | Gaming Mode: 2505 MHz',
                    'cuda_cores' => '5888',
                    'outputs' => 'HDMI 2.1a (x1), DisplayPort 1.4a (x3)',
                    'power_connector' => '1x 16-pin (12VHPWR)',
                    'recommended_psu' => '750W',
                    'cooling' => '3x Axial-tech Fans, MaxContact heatsink, Vapor Chamber',
                    'features' => 'Aura Sync RGB, Dual BIOS, 0dB technology, Axial-tech fan design',
                    'dimensions' => '318.5 x 140.1 x 62.8 mm',
                    'slot_size' => '2.7-slot',
                    'warranty' => '3 năm'
                ]),
                'brand_id' => 1, // ASUS
                'category_id' => 5, // VGA
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ASUS ROG Strix GeForce RTX 5090 Astral OC Edition 24GB GDDR7',
                'slug' => 'asus-rog-strix-rtx-5090-astral-oc-24gb-gddr7-p.44',
                'description' => 'BEST-IN-CLASS: Flagship GPU ASUS ROG Strix RTX 5090 Astral OC với 24GB GDDR7, hệ thống làm mát Liquid Metal + 3.5-slot heatsink, xung nhịp cực cao 2.8GHz+, thiết kế Astral RGB độc quyền cho hiệu năng 8K/VR cực đỉnh',
                'price' => 49990000,
                'import_price' => 42000000,
                'line_price' => 52990000,
                'stock' => 5,
                'images' => json_encode([
                    'vga/asus-rtx-5090-astral.png',
                    'vga/asus-rtx-5090-astral-1.png',
                    'vga/asus-rtx-5090-astral-2.png',
                    'vga/asus-rtx-5090-astral-3.png',
                    'vga/asus-rtx-5090-astral-4.png',
                    'vga/asus-rtx-5090-astral-5.png',
                    'vga/asus-rtx-5090-astral-6.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'chipset' => 'NVIDIA GeForce RTX 5090',
                    'architecture' => 'Blackwell',
                    'memory' => '24GB GDDR7',
                    'memory_bus' => '384-bit',
                    'memory_speed' => '28Gbps',
                    'gpu_clock' => 'OC Mode: 2850 MHz | Gaming Mode: 2805 MHz',
                    'cuda_cores' => '18432',
                    'rt_cores' => '3rd Gen',
                    'tensor_cores' => '4th Gen',
                    'outputs' => 'HDMI 2.1b (x2), DisplayPort 2.1 (x3)',
                    'power_connector' => 'Dual 16-pin (12V-2x6)',
                    'recommended_psu' => '1200W',
                    'cooling' => 'Liquid Metal + 3.5-slot Quantum-Delta Heatsink + 4x 110mm Axial-tech Fans',
                    'rgb' => 'Astral Infinity Mirror LED, Aura Sync',
                    'features' => 'OLED status panel, Dual BIOS, Anti-sag bracket included',
                    'dimensions' => '357.6 x 149.3 x 72.1 mm',
                    'weight' => '2.4kg',
                    'warranty' => '5 năm cao cấp'
                ]),
                'brand_id' => 1, // ASUS
                'category_id' => 5, // VGA
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 1. Samsung 990 Pro 2TB
            [
                'name' => 'Samsung 990 Pro 2TB NVMe PCIe 4.0 M.2',
                'slug' => 'samsung-990-pro-2tb-nvme-pcie-p.45',
                'description' => 'SSD NVMe PCIe 4.0 flagship của Samsung, tốc độ đọc 7450MB/s, công nghệ V-NAND TLC, tối ưu cho game thủ và creator',
                'price' => 5490000,
                'import_price' => 4800000,
                'line_price' => 5990000,
                'stock' => 18,
                'images' => json_encode([
                    'ssd/kingston.png',
                    'ssd/ss990p-1.jpg',
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'capacity' => '2TB',
                    'interface' => 'PCIe 4.0 x4',
                    'controller' => 'Samsung Pascal',
                    'nand_type' => '3D V-NAND TLC',
                    'read_speed' => '7450MB/s',
                    'write_speed' => '6900MB/s',
                    'random_read' => '1200K IOPS',
                    'random_write' => '1550K IOPS',
                    'tbw' => '1200TB',
                    'cooling' => 'Heatsink option',
                    'dimensions' => '80.15 x 22.15 x 2.38 mm'
                ]),
                'brand_id' => 2, // Samsung
                'category_id' => 6, // SSD
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 3. Crucial T700 2TB (PCIe 5.0)
            [
                'name' => 'Crucial T700 2TB NVMe PCIe 5.0',
                'slug' => 'crucial-t700-2tb-pcie5-p.46',
                'description' => 'SSD PCIe 5.0 siêu tốc với hiệu năng đọc 12,400MB/s, đi kèm tản nhiệt active cooling',
                'price' => 8990000,
                'import_price' => 7500000,
                'line_price' => 9990000,
                'stock' => 8,
                'images' => json_encode([
                    'ssd/crucial.webp'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'capacity' => '2TB',
                    'interface' => 'PCIe 5.0 x4',
                    'read_speed' => '12400MB/s',
                    'write_speed' => '11800MB/s',
                    'nand_type' => '3D TLC',
                    'cooling' => 'Active heatsink with fan',
                    'dimensions' => '80 x 23.5 x 15.7 mm'
                ]),
                'brand_id' => 9, // Crucial
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 2. WD Black SN850X 1TB
            [
                'name' => 'WD Black SN850X 1TB NVMe PCIe 4.0',
                'slug' => 'wd-black-sn850x-1tb-nvme-p.47',
                'description' => 'SSD gaming hiệu năng cao với công nghệ DirectStorage, tối ưu cho PS5 và PC gaming',
                'price' => 3290000,
                'import_price' => 2800000,
                'line_price' => 3590000,
                'stock' => 25,
                'images' => json_encode([
                    'ssd/wdbl.jpg',
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'capacity' => '1TB',
                    'interface' => 'PCIe 4.0 x4',
                    'nand_type' => '3D TLC',
                    'read_speed' => '7300MB/s',
                    'write_speed' => '6300MB/s',
                    'random_read' => '800K IOPS',
                    'tbw' => '600TB',
                    'cooling' => 'Heatsink version available',
                    'compatibility' => 'PS5/PC'
                ]),
                'brand_id' => 3, // WD
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 3. Crucial T700 2TB (PCIe 5.0)
            [
                'name' => 'Crucial T700 2TB NVMe PCIe 5.0',
                'slug' => 'crucial-t700-2tb-pcie5-p.48',
                'description' => 'SSD PCIe 5.0 siêu tốc với hiệu năng đọc 12,400MB/s, đi kèm tản nhiệt active cooling',
                'price' => 8990000,
                'import_price' => 7500000,
                'line_price' => 9990000,
                'stock' => 8,
                'images' => json_encode([
                    'ssd/crucial.webp'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'capacity' => '2TB',
                    'interface' => 'PCIe 5.0 x4',
                    'read_speed' => '12400MB/s',
                    'write_speed' => '11800MB/s',
                    'nand_type' => '3D TLC',
                    'cooling' => 'Active heatsink with fan',
                    'dimensions' => '80 x 23.5 x 15.7 mm'
                ]),
                'brand_id' => 9, // Crucial
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],


// 4. Kingston Fury Renegade 1TB
            [
                'name' => 'Kingston Fury Renegade 1TB NVMe PCIe 4.0',
                'slug' => 'kingston-fury-renegade-1tb-p.49',
                'description' => 'SSD gaming hiệu năng cao với thiết kế RGB, tương thích với hệ thống Kingston Fury',
                'price' => 2890000,
                'import_price' => 2400000,
                'line_price' => 3190000,
                'stock' => 15,
                'images' => json_encode([
                    'ssd/kingston.png',
                    'ssd/kingston1.png',
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'capacity' => '1TB',
                    'interface' => 'PCIe 4.0 x4',
                    'read_speed' => '7300MB/s',
                    'write_speed' => '6000MB/s',
                    'rgb' => 'Fury RGB controllable',
                    'tbw' => '800TB'
                ]),
                'brand_id' => 5, // Kingston
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 5. Seagate FireCuda 530 1TB
            [
                'name' => 'Seagate FireCuda 530 1TB NVMe PCIe 4.0',
                'slug' => 'seagate-firecuda-530-1tb-p.50',
                'description' => 'SSD cao cấp với bảo hành 5 năm, tối ưu cho workstation và game thủ chuyên nghiệp',
                'price' => 3290000,
                'import_price' => 2900000,
                'line_price' => 3590000,
                'stock' => 12,
                'images' => json_encode([
                    'ssd/kingston.png',
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'capacity' => '1TB',
                    'interface' => 'PCIe 4.0 x4',
                    'read_speed' => '7300MB/s',
                    'write_speed' => '6000MB/s',
                    'warranty' => '5 years',
                    'tbw' => '1275TB'
                ]),
                'brand_id' => 6, // Seagate
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 6. ADATA Legend 960 2TB
            [
                'name' => 'ADATA Legend 960 2TB NVMe PCIe 4.0',
                'slug' => 'adata-legend-960-2tb-p.51',
                'description' => 'SSD NVMe giá tốt với hiệu năng ổn định, phù hợp cho nâng cấp laptop và PC',
                'price' => 3790000,
                'import_price' => 3200000,
                'line_price' => 4190000,
                'stock' => 20,
                'images' => json_encode([
                    'ssd/kingston.png',
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'capacity' => '2TB',
                    'interface' => 'PCIe 4.0 x4',
                    'read_speed' => '5000MB/s',
                    'write_speed' => '4500MB/s',
                    'tbw' => '800TB'
                ]),
                'brand_id' => 7, // ADATA
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 7. Teamgroup MP44L 1TB
            [
                'name' => 'Teamgroup MP44L 1TB NVMe PCIe 4.0',
                'slug' => 'teamgroup-mp44l-1tb-p.52',
                'description' => 'SSD NVMe giá rẻ với hiệu năng cơ bản, phù hợp cho văn phòng và sử dụng thông thường',
                'price' => 1890000,
                'import_price' => 1500000,
                'line_price' => 2090000,
                'stock' => 30,
                'images' => json_encode([
                    'ssd/kingston.png',
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'capacity' => '1TB',
                    'interface' => 'PCIe 4.0 x4',
                    'read_speed' => '5000MB/s',
                    'write_speed' => '4000MB/s',
                    'tbw' => '500TB'
                ]),
                'brand_id' => 8, // Teamgroup
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 8. Lexar NM790 2TB
            [
                'name' => 'Lexar NM790 2TB NVMe PCIe 4.0',
                'slug' => 'lexar-nm790-2tb-p.53',
                'description' => 'SSD NVMe hiệu năng cao với giá thành cạnh tranh, hỗ trợ công nghệ LDPC ECC',
                'price' => 4290000,
                'import_price' => 3800000,
                'line_price' => 4590000,
                'stock' => 15,
                'images' => json_encode([
                    'ssd/kingston.png',
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'capacity' => '2TB',
                    'interface' => 'PCIe 4.0 x4',
                    'read_speed' => '7400MB/s',
                    'write_speed' => '6500MB/s',
                    'tbw' => '1000TB'
                ]),
                'brand_id' => 10, // Lexar
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 9. Gigabyte AORUS 7000s 1TB
            [
                'name' => 'Gigabyte AORUS 7000s 1TB NVMe PCIe 4.0',
                'slug' => 'gigabyte-aorus-7000s-1tb-p.54',
                'description' => 'SSD gaming với bộ tản nhiệt đồng nguyên khối, tối ưu cho hệ thống AORUS',
                'price' => 3490000,
                'import_price' => 3000000,
                'line_price' => 3790000,
                'stock' => 10,
                'images' => json_encode([
                    'ssd/kingston.png',
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'capacity' => '1TB',
                    'interface' => 'PCIe 4.0 x4',
                    'read_speed' => '7000MB/s',
                    'write_speed' => '5500MB/s',
                    'cooling' => 'Copper heatsink'
                ]),
                'brand_id' => 4, // Gigabyte
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 10. Kioxia Exceria Pro 1TB
            [
                'name' => 'Kioxia Exceria Pro 1TB NVMe PCIe 4.0',
                'slug' => 'kioxia-exceria-pro-1tb-p.55',
                'description' => 'SSD NVMe từ nhà sản xuất NAND hàng đầu, hiệu năng ổn định với độ bền cao',
                'price' => 2790000,
                'import_price' => 2300000,
                'line_price' => 2990000,
                'stock' => 18,
                'images' => json_encode([
                    'ssd/kingston.png',
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'capacity' => '1TB',
                    'interface' => 'PCIe 4.0 x4',
                    'read_speed' => '7300MB/s',
                    'write_speed' => '6400MB/s',
                    'tbw' => '800TB'
                ]),
                'brand_id' => 12, // Kioxia
                'category_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 1. Corsair CX650F RGB (650W) - Bronze
            [
                'name' => 'Corsair CX650F RGB 650W 80 Plus Bronze',
                'slug' => 'corsair-cx650f-rgb-650w-bronze-p.56',
                'description' => 'Nguồn máy tính 650W chuẩn 80 Plus Bronze với hệ thống làm mát quạt RGB, dây cáp phẳng full-modular',
                'price' => 1890000,
                'import_price' => 1500000,
                'line_price' => 2090000,
                'stock' => 15,
                'images' => json_encode([
                    '/psu/650.webp'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'wattage' => '650W',
                    'efficiency' => '80 Plus Bronze',
                    'modular' => 'Full Modular',
                    'fan' => '120mm RGB Fan',
                    'connectors' => '1x 24-pin ATX, 2x 8-pin EPS, 2x PCIe 8-pin, 4x SATA, 3x Molex',
                    'protections' => 'OVP, UVP, OPP, SCP, OTP',
                    'warranty' => '5 năm'
                ]),
                'brand_id' => 13, // Corsair
                'category_id' => 7, // PSU
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 2. Cooler Master MWE Gold 550 V2 (550W) - Gold
            [
                'name' => 'Cooler Master MWE Gold 550 V2 550W 80 Plus Gold',
                'slug' => 'cooler-master-mwe-gold-550-v2-p.57',
                'description' => 'Nguồn 550W hiệu suất cao 80 Plus Gold, thiết kế silent fan với chế độ Zero Fan dưới 15% tải',
                'price' => 1590000,
                'import_price' => 1300000,
                'line_price' => 1790000,
                'stock' => 20,
                'images' => json_encode([
                    '/psu/psu.png'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'wattage' => '550W',
                    'efficiency' => '80 Plus Gold',
                    'modular' => 'Non-modular',
                    'fan' => '120mm Silent Fan',
                    'connectors' => '1x 24-pin ATX, 1x 8-pin EPS, 1x PCIe 8-pin, 4x SATA',
                    'protections' => 'OVP, UVP, OPP, SCP',
                    'features' => 'Zero Fan Mode <15% load'
                ]),
                'brand_id' => 14, // Cooler Master
                'category_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 3. MSI MAG A550BN (550W) - Bronze
            [
                'name' => 'MSI MAG A550BN 550W 80 Plus Bronze',
                'slug' => 'msi-mag-a550bn-550w-p.58',
                'description' => 'Nguồn 550W ổn định với chứng nhận 80 Plus Bronze, thiết kế tản nhiệt tốt cho hệ thống gaming',
                'price' => 1390000,
                'import_price' => 1100000,
                'line_price' => 1490000,
                'stock' => 25,
                'images' => json_encode([
                    '/psu/msi.png'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'wattage' => '550W',
                    'efficiency' => '80 Plus Bronze',
                    'modular' => 'Non-modular',
                    'fan' => '120mm Hydraulic Bearing Fan',
                    'protections' => 'OVP, UVP, OPP, SCP, OCP'
                ]),
                'brand_id' => 15, // MSI
                'category_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 4. FSP Hydro GD 650W - Gold
            [
                'name' => 'FSP Hydro GD 650W 80 Plus Gold',
                'slug' => 'fsp-hydro-gd-650w-gold-p.59',
                'description' => 'Nguồn 650W hiệu suất Gold, thiết kế bảo vệ chống ẩm với công nghệ Hydro Bearing',
                'price' => 1790000,
                'import_price' => 1450000,
                'line_price' => 1990000,
                'stock' => 12,
                'images' => json_encode([
                    '/psu/psu.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'wattage' => '650W',
                    'efficiency' => '80 Plus Gold',
                    'modular' => 'Semi-modular',
                    'fan' => '120mm Hydro Bearing Fan',
                    'features' => 'Anti-humidity coating',
                    'protections' => 'Full protections'
                ]),
                'brand_id' => 16, // FSP
                'category_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 5. Gigabyte P550B (550W) - Bronze
            [
                'name' => 'Gigabyte P550B 550W 80 Plus Bronze',
                'slug' => 'gigabyte-p550b-550w-bronze-p.60',
                'description' => 'Nguồn 550W giá tốt với hiệu suất Bronze, phù hợp cho hệ thống gaming cơ bản',
                'price' => 1290000,
                'import_price' => 1000000,
                'line_price' => 1490000,
                'stock' => 30,
                'images' => json_encode([
                    '/psu/giga.webp'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'wattage' => '550W',
                    'efficiency' => '80 Plus Bronze',
                    'modular' => 'Non-modular',
                    'fan' => '120mm Smart Fan',
                    'protections' => 'OVP, UVP, OPP, SCP'
                ]),
                'brand_id' => 4, // Gigabyte
                'category_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 1. Case NZXT H7 Flow (High-End Airflow)
            [
                'name' => 'NZXT H7 Flow Mid-Tower Case (Black)',
                'slug' => 'nzxt-h7-flow-case-black-p.61',
                'description' => 'Case mid-tower cao cấp với thiết kế tản nhiệt airflow tối ưu, hỗ trợ tản nhiệt liquid cooling và VGA dài tới 400mm',
                'price' => 3290000,
                'import_price' => 2800000,
                'line_price' => 3590000,
                'stock' => 10,
                'images' => json_encode([
                    '/case/nzxt.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'type' => 'Mid-Tower',
                    'color' => 'Black',
                    'material' => 'Steel/Tempered Glass',
                    'motherboard' => 'ATX/mATX/mini-ITX',
                    'drive_bays' => '2x 2.5", 2x 3.5"',
                    'expansion_slots' => '7',
                    'max_gpu_length' => '400mm',
                    'max_cpu_cooler' => '185mm',
                    'front_io' => '1x USB-C, 2x USB 3.0, Audio',
                    'fans' => '3x 120mm (pre-installed)',
                    'radiator_support' => '360mm (front)',
                    'weight' => '8.2kg'
                ]),
                'brand_id' => 17, // NZXT
                'category_id' => 8, // Case
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 2. Case Cooler Master MasterBox Q300L (Budget)
            [
                'name' => 'Cooler Master MasterBox Q300L mATX Case',
                'slug' => 'cooler-master-q300l-case-p.62',
                'description' => 'Case mini-tower giá rẻ với thiết kế tối giản, hỗ trợ bố trí quạt linh hoạt và VGA dài tới 360mm',
                'price' => 990000,
                'import_price' => 750000,
                'line_price' => 1190000,
                'stock' => 25,
                'images' => json_encode([
                    'case/cooler.webp'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'type' => 'Micro-ATX',
                    'color' => 'Black',
                    'material' => 'ABS/Steel',
                    'motherboard' => 'mATX/mini-ITX',
                    'drive_bays' => '2x 2.5", 1x 3.5"',
                    'max_gpu_length' => '360mm',
                    'max_cpu_cooler' => '160mm',
                    'front_io' => '2x USB 3.0, Audio',
                    'fans' => '1x 120mm (rear)',
                    'weight' => '4.5kg'
                ]),
                'brand_id' => 14, // Cooler Master
                'category_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 3. Case Lian Li PC-O11 Dynamic (Premium)
            [
                'name' => 'Lian Li PC-O11 Dynamic White',
                'slug' => 'lian-li-pc-o11-white-p.63',
                'description' => 'Case full-tower cao cấp với thiết kế dual-chamber, 2 mặt kính cường lực, tối ưu cho custom water cooling',
                'price' => 4990000,
                'import_price' => 4200000,
                'line_price' => 5490000,
                'stock' => 8,
                'images' => json_encode([
                    '/case/lian.jpg'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'type' => 'Full-Tower',
                    'color' => 'White',
                    'material' => 'Aluminum/Tempered Glass',
                    'motherboard' => 'E-ATX/ATX/mATX',
                    'drive_bays' => '6x 2.5", 2x 3.5"',
                    'max_gpu_length' => '420mm',
                    'max_cpu_cooler' => '170mm',
                    'front_io' => '2x USB 3.0, 1x USB-C, Audio',
                    'radiator_support' => 'Triple 360mm',
                    'weight' => '12.6kg',
                    'special_features' => 'Dual-chamber design'
                ]),
                'brand_id' => 18, // Lian Li
                'category_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 4. Case Corsair 4000D Airflow (Mid-Range)
            [
                'name' => 'Corsair 4000D Airflow Tempered Glass',
                'slug' => 'corsair-4000d-airflow-p.64',
                'description' => 'Case mid-tower tập trung vào khả năng airflow với thiết kế tối giản, hỗ trợ tản nhiệt AIO 360mm',
                'price' => 2490000,
                'import_price' => 2000000,
                'line_price' => 2790000,
                'stock' => 15,
                'images' => json_encode([
                    '/case/corsair.webp'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'type' => 'Mid-Tower',
                    'color' => 'Black',
                    'material' => 'Steel/Tempered Glass',
                    'motherboard' => 'ATX/mATX/mini-ITX',
                    'drive_bays' => '2x 2.5", 2x 3.5"',
                    'max_gpu_length' => '360mm',
                    'max_cpu_cooler' => '170mm',
                    'fans' => '2x 120mm (pre-installed)',
                    'radiator_support' => '360mm (front)',
                    'weight' => '7.4kg'
                ]),
                'brand_id' => 13, // Corsair
                'category_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 5. Case Deepcool MATREXX 55 (RGB)
            [
                'name' => 'Deepcool MATREXX 55 Mesh ADD-RGB',
                'slug' => 'deepcool-matrexx-55-rgb-p.65',
                'description' => 'Case ATX giá tốt với 4 quạt RGB tích hợp, mặt lưới tản nhiệt và tương thích với mainboard ARGB',
                'price' => 1490000,
                'import_price' => 1200000,
                'line_price' => 1690000,
                'stock' => 20,
                'images' => json_encode([
                    '/case/deep.webp'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'type' => 'Mid-Tower',
                    'color' => 'Black',
                    'material' => 'Steel/Tempered Glass',
                    'motherboard' => 'ATX/mATX/mini-ITX',
                    'drive_bays' => '2x 2.5", 2x 3.5"',
                    'max_gpu_length' => '370mm',
                    'fans' => '4x 120mm ARGB (pre-installed)',
                    'rgb_sync' => 'ARGB compatible',
                    'weight' => '6.8kg'
                ]),
                'brand_id' => 19, // Deepcool
                'category_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 1. Màn hình gaming cao cấp
            [
                'name' => 'LG UltraGear 27GP850-B 27" QHD Nano IPS 165Hz',
                'slug' => 'lg-ultragear-27gp850-p.66',
                'description' => 'Màn hình gaming 27" QHD (2560x1440) tần số cao 165Hz (OC 180Hz), công nghệ Nano IPS, hỗ trợ G-Sync Compatible và FreeSync Premium',
                'price' => 8990000,
                'import_price' => 7500000,
                'line_price' => 9990000,
                'stock' => 9,
                'images' => json_encode([
                    '/monitor/lg27.webp',
                    '/monitor/lg271.webp',
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'screen_size' => '27"',
                    'resolution' => '2560x1440 (QHD)',
                    'panel_type' => 'Nano IPS',
                    'refresh_rate' => '165Hz (OC 180Hz)',
                    'response_time' => '1ms GTG',
                    'brightness' => '400 nits',
                    'hdr' => 'HDR10',
                    'adaptive_sync' => 'G-Sync Compatible, FreeSync Premium',
                    'ports' => '2x HDMI 2.0, 1x DisplayPort 1.4, 2x USB 3.0',
                    'stand_adjustment' => 'Height/Tilt/Swivel/Pivot'
                ]),
                'brand_id' => 1, // LG
                'category_id' => 9, // Màn hình
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 2. Màn hình giá rẻ văn phòng
            [
                'name' => 'AOC 24B2XH 23.8" Full HD IPS',
                'slug' => 'aoc-24b2xh-p.67',
                'description' => 'Màn hình văn phòng 23.8" Full HD (1920x1080) tấm nền IPS, viền mỏng, tiết kiệm điện năng',
                'price' => 3290000,
                'import_price' => 2800000,
                'line_price' => 3690000,
                'stock' => 30,
                'images' => json_encode([
                    '/monitor/aoc.webp'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'screen_size' => '23.8"',
                    'resolution' => '1920x1080 (FHD)',
                    'panel_type' => 'IPS',
                    'refresh_rate' => '75Hz',
                    'response_time' => '5ms',
                    'brightness' => '250 nits',
                    'ports' => '1x HDMI 1.4, 1x VGA',
                    'stand_adjustment' => 'Tilt only'
                ]),
                'brand_id' => 1, // AOC
                'category_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 3. Màn hình UltraWide chuyên nghiệp
            [
                'name' => 'Samsung Odyssey G9 49" DQHD 240Hz Curved',
                'slug' => 'samsung-odyssey-g9-p.68',
                'description' => 'Màn hình gaming siêu rộng 49" độ cong 1000R, độ phân giải Dual QHD (5120x1440), tần số 240Hz, công nghệ QLED và HDR2000',
                'price' => 34990000,
                'import_price' => 30000000,
                'line_price' => 36990000,
                'stock' => 3,
                'images' => json_encode([
                    'monitor/ssutr.avif'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'screen_size' => '49"',
                    'resolution' => '5120x1440 (DQHD)',
                    'panel_type' => 'VA QLED',
                    'refresh_rate' => '240Hz',
                    'response_time' => '1ms',
                    'curvature' => '1000R',
                    'brightness' => '1000 nits (HDR2000)',
                    'hdr' => 'HDR2000',
                    'adaptive_sync' => 'G-Sync Compatible, FreeSync Premium Pro',
                    'ports' => '1x DisplayPort 1.4, 2x HDMI 2.1'
                ]),
                'brand_id' => 1, // Samsung
                'category_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 4. Màn hình 4K đồ họa
            [
                'name' => 'Dell UltraSharp U2723QE 27" 4K IPS Black',
                'slug' => 'dell-u2723qe-p.69',
                'description' => 'Màn hình chuyên đồ họa 27" độ phân giải 4K UHD (3840x2160), tấm nền IPS Black, độ phủ màu 98% DCI-P3, USB-C 90W',
                'price' => 14990000,
                'import_price' => 13000000,
                'line_price' => 15990000,
                'stock' => 12,
                'images' => json_encode([
                    'monitor/dellvip.avif',
                    'monitor/dellvip1.avif',
                    'monitor/dellvip2.avif',
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'screen_size' => '27"',
                    'resolution' => '3840x2160 (4K UHD)',
                    'panel_type' => 'IPS Black',
                    'refresh_rate' => '60Hz',
                    'response_time' => '5ms',
                    'color_gamut' => '98% DCI-P3',
                    'brightness' => '400 nits',
                    'hdr' => 'HDR400',
                    'ports' => '1x USB-C (90W), 1x DisplayPort, 2x HDMI, 4x USB 3.0',
                    'stand_adjustment' => 'Height/Tilt/Swivel/Pivot'
                ]),
                'brand_id' => 1, // Dell
                'category_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 5. Màn hình gaming giá rẻ
            [
                'name' => 'MSI Optix G241 23.8" Full HD IPS 144Hz',
                'slug' => 'msi-optix-g241-p.70',
                'description' => 'Màn hình gaming 23.8" Full HD (1920x1080) tần số 144Hz, tấm nền IPS, hỗ trợ FreeSync Premium, thiết kế viền mỏng',
                'price' => 4990000,
                'import_price' => 4200000,
                'line_price' => 5490000,
                'stock' => 18,
                'images' => json_encode([
                    '/monitor/msi.png',
                    '/monitor/msi1.png',
                    '/monitor/msi2.png'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'screen_size' => '23.8"',
                    'resolution' => '1920x1080 (FHD)',
                    'panel_type' => 'IPS',
                    'refresh_rate' => '144Hz',
                    'response_time' => '1ms',
                    'adaptive_sync' => 'FreeSync Premium',
                    'brightness' => '300 nits',
                    'ports' => '1x DisplayPort 1.2, 2x HDMI 1.4'
                ]),
                'brand_id' => 1, // MSI
                'category_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 6. Màn hình 2K giá tốt
            [
                'name' => 'ViewSonic VX2758-2KP-MHD 27" QHD IPS 144Hz',
                'slug' => 'viewsonic-vx2758-p.71',
                'description' => 'Màn hình gaming 27" QHD (2560x1440) tần số 144Hz, tấm nền IPS, hỗ trợ FreeSync, thiết kế không viền 3 cạnh',
                'price' => 5990000,
                'import_price' => 5000000,
                'line_price' => 6490000,
                'stock' => 15,
                'images' => json_encode([
                    '/monitor/view.jpg',
                    '/monitor/view1.jpg',
                    '/monitor/view2.jpg',
                    '/monitor/view3.jpg',
                    '/monitor/view4.jpg'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'screen_size' => '27"',
                    'resolution' => '2560x1440 (QHD)',
                    'panel_type' => 'IPS',
                    'refresh_rate' => '144Hz',
                    'response_time' => '1ms',
                    'adaptive_sync' => 'FreeSync',
                    'brightness' => '350 nits',
                    'ports' => '2x HDMI 2.0, 1x DisplayPort 1.2'
                ]),
                'brand_id' => 1, // ViewSonic
                'category_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 7. Màn hình cong chơi game
            [
                'name' => 'ASUS TUF Gaming VG27WQ 27" QHD Curved 165Hz',
                'slug' => 'asus-tuf-vg27wq-p.72',
                'description' => 'Màn hình gaming cong 27" độ phân giải QHD (2560x1440), tần số 165Hz, tấm nền VA, hỗ trợ ELMB Sync và FreeSync Premium',
                'price' => 7990000,
                'import_price' => 6800000,
                'line_price' => 8490000,
                'stock' => 10,
                'images' => json_encode([
                    '/monitor/asus.png',
                    '/monitor/asus1.png',
                    '/monitor/asus2.png',
                    '/monitor/asus3.png'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'screen_size' => '27"',
                    'resolution' => '2560x1440 (QHD)',
                    'panel_type' => 'VA',
                    'refresh_rate' => '165Hz',
                    'response_time' => '1ms MPRT',
                    'curvature' => '1500R',
                    'adaptive_sync' => 'FreeSync Premium',
                    'brightness' => '400 nits',
                    'hdr' => 'HDR10',
                    'ports' => '1x DisplayPort 1.2, 2x HDMI 2.0'
                ]),
                'brand_id' => 1, // ASUS
                'category_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 8. Màn hình văn phòng tiết kiệm
            [
                'name' => 'HP M24F 23.8" Full HD IPS',
                'slug' => 'hp-m24f0-p.73',
                'description' => 'Màn hình văn phòng 23.8" Full HD (1920x1080) tấm nền IPS, thiết kế không viền 3 cạnh, tiết kiệm điện năng',
                'price' => 3490000,
                'import_price' => 3000000,
                'line_price' => 3890000,
                'stock' => 25,
                'images' => json_encode([
                    '/monitor/hp.webp',
                    '/monitor/hp1.webp',
                    '/monitor/hp2.webp',
                    '/monitor/hp3.webp'
                ]),
                'is_featured' => false,
                'is_active' => true,
                'specs' => json_encode([
                    'screen_size' => '23.8"',
                    'resolution' => '1920x1080 (FHD)',
                    'panel_type' => 'IPS',
                    'refresh_rate' => '75Hz',
                    'response_time' => '5ms',
                    'brightness' => '250 nits',
                    'ports' => '1x HDMI 1.4, 1x VGA'
                ]),
                'brand_id' => 1, // HP
                'category_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 9. Màn hình chuyên đồ họa cao cấp
            [
                'name' => 'BenQ PD2705U 27" 4K IPS',
                'slug' => 'benq-pd2705u-p.74',
                'description' => 'Màn hình chuyên nghiệp 27" 4K UHD (3840x2160) tấm nền IPS, độ phủ màu 99% sRGB & Rec.709, công nghệ AQCOLOR',
                'price' => 16990000,
                'import_price' => 14500000,
                'line_price' => 17990000,
                'stock' => 7,
                'images' => json_encode([
                    '/monitor/benq.webp'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'screen_size' => '27"',
                    'resolution' => '3840x2160 (4K UHD)',
                    'panel_type' => 'IPS',
                    'color_gamut' => '99% sRGB, 99% Rec.709',
                    'color_accuracy' => 'Delta E ≤ 3',
                    'brightness' => '350 nits',
                    'hdr' => 'HDR10',
                    'ports' => '1x USB-C (65W), 1x DisplayPort, 2x HDMI, 3x USB 3.0',
                    'special_features' => 'Pantone Validated, Calman Verified'
                ]),
                'brand_id' => 1, // BenQ
                'category_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],

// 10. Màn hình gaming siêu nhanh
            [
                'name' => 'Acer Predator XB273U 27" QHD IPS 240Hz',
                'slug' => 'acer-predator-xb273u-p.75',
                'description' => 'Màn hình gaming chuyên nghiệp 27" QHD (2560x1440) tần số 240Hz, tấm nền IPS, hỗ trợ NVIDIA G-SYNC, độ phản hồi 0.5ms',
                'price' => 18990000,
                'import_price' => 16000000,
                'line_price' => 19990000,
                'stock' => 5,
                'images' => json_encode([
                    '/monitor/acer.jpg',
                    '/monitor/acer1.jpg',
                    '/monitor/acer2.jpg',
                    '/monitor/acer3.jpg'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'screen_size' => '27"',
                    'resolution' => '2560x1440 (QHD)',
                    'panel_type' => 'IPS',
                    'refresh_rate' => '240Hz',
                    'response_time' => '0.5ms (GTG)',
                    'adaptive_sync' => 'NVIDIA G-SYNC',
                    'brightness' => '400 nits',
                    'hdr' => 'HDR400',
                    'ports' => '1x DisplayPort 1.4, 2x HDMI 2.0',
                    'special_features' => 'Predator GameView technology'
                ]),
                'brand_id' => 1, // Acer
                'category_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Attack Shark X11 wireless Gaming Mouse',
                'slug' => 'atk-shark-x11-p.76',
                'description' => '46g Magnesium Alloy SUPERLIGHT Mouse, 8000Hz Wireless Polling Rate, 6 Adjustable DPI up to 26000, PixArt PAW3395 Gaming Sensor, BT/2.4G Wireless/Wired',
                'price' => 999000,
                'import_price' => 500000,
                'line_price' => 1299000,
                'stock' => 5,
                'images' => json_encode([
                    '/gear/atkx12.webp',
                    '/gear/atkx11.webp',
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'DPI' => '5000',
                ]),
                'brand_id' => 1, // Acer
                'category_id' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ATTACK SHARK R3 Magnesium Alloy Gaming',
                'slug' => 'atk-shark-r3-p.77',
                'description' => '46g Magnesium Alloy SUPERLIGHT Mouse, 8000Hz Wireless Polling Rate, 6 Adjustable DPI up to 26000, PixArt PAW3395 Gaming Sensor, BT/2.4G Wireless/Wired',
                'price' => 999000,
                'import_price' => 500000,
                'line_price' => 1299000,
                'stock' => 5,
                'images' => json_encode([
                    '/gear/atkx2.webp'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'DPI' => '5000',
                ]),
                'brand_id' => 1, // Acer
                'category_id' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ATTACK SHARK X85 Wireless Mechanical Keyboard',
                'slug' => 'atk-shark-x85-p.78',
                'description' => '75% Gasket-mounted Bluetooth 5.1/2.4G Wireless & Type-C Wired Mechanical Keyboard',
                'price' => 1592000,
                'import_price' => 1200000,
                'line_price' => 1990000,
                'stock' => 5,
                'images' => json_encode([
                    '/gear/atkk.webp'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'DPI' => '5000',
                ]),
                'brand_id' => 1, // Acer
                'category_id' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ATTACK SHARK M87PRO Wireless Mechanical Keyboard with Side Printed PBT Keycaps',
                'slug' => 'atk-shark-m87-p.79',
                'description' => '80% Gasket-mounted Mechanical Keyboard.',
                'price' => 1592000,
                'import_price' => 1200000,
                'line_price' => 1990000,
                'stock' => 5,
                'images' => json_encode([
                    '/gear/atkk1.webp'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'DPI' => '5000',
                ]),
                'brand_id' => 1, // Acer
                'category_id' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'ATTACK SHARK L80PRO Wireless Gaming Headset',
                'slug' => 'atk-shark-l80-p.80',
                'description' => '80% Gasket-mounted Mechanical Keyboard.',
                'price' => 1592000,
                'import_price' => 1200000,
                'line_price' => 1990000,
                'stock' => 5,
                'images' => json_encode([
                    '/gear/head1.webp'
                ]),
                'is_featured' => true,
                'is_active' => true,
                'specs' => json_encode([
                    'DPI' => '5000',
                ]),
                'brand_id' => 1, // Acer
                'category_id' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],


        ];
        DB::table('products')->insert($cpus);
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
