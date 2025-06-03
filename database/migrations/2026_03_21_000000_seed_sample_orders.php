<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

return new class extends Migration
{
    public function up()
    {
        // Lấy user đầu tiên để tạo đơn hàng
        $user = User::first();
        if (!$user) {
            return;
        }

        // Lấy danh sách sản phẩm
        $products = Product::all();
        if ($products->isEmpty()) {
            return;
        }

        // Tạo 100 đơn hàng mẫu
        for ($i = 1; $i <= 2; $i++) {
            // Tạo mã đơn hàng duy nhất bằng cách thêm timestamp
            $timestamp = Carbon::now()->timestamp;
            $orderNumber = 'ORD' . $timestamp . str_pad($i, 3, '0', STR_PAD_LEFT);

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $orderNumber,
                'customer_name' => 'Khách hàng ' . $i,
                'phone' => '0' . rand(100000000, 999999999),
                'email' => 'customer' . $i . '@example.com',
                'address' => 'Địa chỉ ' . $i . ', Quận ' . rand(1, 12) . ', TP.HCM',
                'notes' => rand(0, 1) ? 'Ghi chú cho đơn hàng ' . $i : null,
                'payment_method' => rand(0, 1) ? 'vnpay' : 'cod',
                'status' => $this->getRandomStatus(),
                'total' => 0, // Sẽ cập nhật sau
                'created_at' => Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59)),
                'updated_at' => Carbon::now(),
            ]);

            // Tạo các sản phẩm trong đơn hàng
            $total = 0;
            $numItems = rand(1, 5); // Mỗi đơn hàng có 1-5 sản phẩm
            $selectedProducts = $products->random($numItems);

            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 3);
                $price = $product->price;
                $subtotal = $price * $quantity;
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                ]);
            }

            // Cập nhật tổng tiền đơn hàng
            $order->update(['total' => $total]);
        }
    }

    public function down()
    {
        // Xóa tất cả đơn hàng mẫu
        Order::where('order_number', 'like', 'ORD%')->delete();
    }

    private function getRandomStatus()
    {
        $statuses = ['pending', 'processing', 'confirmed', 'completed', 'cancelled'];
        $weights = [20, 20, 20, 20, 20]; // Tỷ lệ xuất hiện của mỗi trạng thái

        $totalWeight = array_sum($weights);
        $random = rand(1, $totalWeight);
        $currentWeight = 0;

        foreach ($statuses as $index => $status) {
            $currentWeight += $weights[$index];
            if ($random <= $currentWeight) {
                return $status;
            }
        }

        return 'pending'; // Mặc định nếu có lỗi
    }
};
