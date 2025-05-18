<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class OrderController extends Controller
{
    public function getUserOrders()
    {
        $userId = Auth::user()->id;
        $orders = Order::where('user_id', $userId)->get();
        return Inertia::render('Order', [
            'orders' => $orders
        ]);
    }
    public function create(Request $request)
    {
//        dd($request->all());
        // Validate input
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'payment_method' => 'required|string|in:card,cod',
            'products' => 'required|array',
        ]);
        // Create the order
        $order = Order::create([
            'user_id' => auth()->id(),
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->phone,
            'address' => $request->address,
            'shipping_address' => $request->address,
            'status' => 'processing', // default status
            'order_number' => 'order' . base64_encode(now()->timestamp),
            'total' => 1000000,
            'notes' => ''
        ]);


        // Add the products to the order
        foreach ($request->products as $product) {
            $productDetail = Product::find($product['id'])->toArray();
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product['id'],
                'product_name' => $productDetail['name'],
                'quantity' => $product['quantity'],
                'price' => $productDetail['price'],
                'subtotal' => $product['quantity'] * $productDetail['price'],
            ]);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => $item->product_name],
                    'unit_amount' => $item->price / 25000, // convert to cents
                ],
                'quantity' => $item->quantity,
            ];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => url("/payment/success?session_id={CHECKOUT_SESSION_ID}"),
            'cancel_url' => url('/payment/cancel'),
            'metadata' => ['order_id' => $order->id],
        ]);

        return response()->json([
            'success' => true,
            'sessionId' => $session->id // Trả về sessionId thay vì redirect
        ]);
    }

    public function cancel($orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->status !== 'processing') {
            return response()->json(['message' => 'Order cannot be cancelled.'], 400);
        }

        // Update the order status to cancelled
        $order->update(['status' => 'cancelled']);

        return redirect()->route('order.index')->with('message', 'Order cancelled successfully.');
    }

}
