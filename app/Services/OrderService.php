<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'user_id' => $data['user_id'] ?? null,
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'shipping_address' => $data['shipping_address'],
                'status' => 'pending',
                'subtotal' => $data['subtotal'],
                'shipping_fee' => $data['shipping_fee'] ?? 0,
                'total' => $data['total'],
                'notes' => $data['notes'] ?? null
            ]);

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['quantity']
                ]);
            }

            return $order;
        });
    }

    public function updateStatus(Order $order, string $status): Order
    {
        $order->status = $status;
        $order->save();
        return $order;
    }

    public function cancelOrder(Order $order): Order
    {
        return $this->updateStatus($order, 'cancelled');
    }

    public function completeOrder(Order $order): Order
    {
        return $this->updateStatus($order, 'completed');
    }
}
