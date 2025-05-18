<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    public function success(Order $order)
    {
        $order->update(['status' => 'paid']);
        return redirect()->route('order.show', $order->id)->with('message', 'Thanh toán thành công!');
    }

    public function cancel(Order $order)
    {
        return redirect()->route('order.show', $order->id)->with('error', 'Bạn đã hủy thanh toán.');
    }
}
