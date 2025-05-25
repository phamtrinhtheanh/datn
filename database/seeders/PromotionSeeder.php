<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
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
            ],
        ];

        foreach ($promotions as $promotion) {
            Promotion::create($promotion);
        }
    }
} 