<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['percentage', 'fixed_amount']);
            $table->decimal('value', 10, 2);
            $table->decimal('min_order_amount', 10, 2);
            $table->integer('max_uses')->nullable();
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert sample data
        $promotions = [
            [
                'code' => 'WELCOME10',
                'name' => 'Chào mừng khách hàng mới',
                'type' => 'percentage',
                'value' => 10,
                'min_order_amount' => 200000,
                'max_uses' => 1000,
                'start_date' => now(),
                'end_date' => now()->addMonths(1),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'SUMMER20',
                'name' => 'Khuyến mãi mùa hè',
                'type' => 'percentage',
                'value' => 20,
                'min_order_amount' => 500000,
                'max_uses' => 500,
                'start_date' => now(),
                'end_date' => now()->addMonths(2),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FLASH50K',
                'name' => 'Flash sale 50K',
                'type' => 'fixed_amount',
                'value' => 50000,
                'min_order_amount' => 300000,
                'max_uses' => 200,
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'VIP15',
                'name' => 'Ưu đãi VIP',
                'type' => 'percentage',
                'value' => 15,
                'min_order_amount' => 1000000,
                'max_uses' => 100,
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'NEW100K',
                'name' => 'Giảm giá 100K cho đơn hàng mới',
                'type' => 'fixed_amount',
                'value' => 100000,
                'min_order_amount' => 500000,
                'max_uses' => 300,
                'start_date' => now(),
                'end_date' => now()->addMonths(1),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'WEEKEND25',
                'name' => 'Ưu đãi cuối tuần',
                'type' => 'percentage',
                'value' => 25,
                'min_order_amount' => 400000,
                'max_uses' => 150,
                'start_date' => now(),
                'end_date' => now()->addMonths(2),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'BIRTHDAY30',
                'name' => 'Quà sinh nhật',
                'type' => 'percentage',
                'value' => 30,
                'min_order_amount' => 300000,
                'max_uses' => 1,
                'start_date' => now(),
                'end_date' => now()->addYears(1),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'FIRST75K',
                'name' => 'Ưu đãi lần đầu',
                'type' => 'fixed_amount',
                'value' => 75000,
                'min_order_amount' => 250000,
                'max_uses' => 1,
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'HOLIDAY40',
                'name' => 'Khuyến mãi ngày lễ',
                'type' => 'percentage',
                'value' => 40,
                'min_order_amount' => 800000,
                'max_uses' => 50,
                'start_date' => now(),
                'end_date' => now()->addMonths(1),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'MEMBER200K',
                'name' => 'Ưu đãi thành viên',
                'type' => 'fixed_amount',
                'value' => 200000,
                'min_order_amount' => 1000000,
                'max_uses' => 80,
                'start_date' => now(),
                'end_date' => now()->addMonths(3),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('promotions')->insert($promotions);
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
