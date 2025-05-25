<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $orders = Order::with(['user', 'items.product'])
            ->latest()
            ->paginate(10);

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return Inertia::render('Admin/Orders/Show', [
            'order' => $order
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,confirmed,completed,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công');
    }

    public function create(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email',
            'address' => 'required|string',
            'notes' => 'nullable|string',
            'payment' => 'required|in:vnpay,cod',
        ]);

        // Get checkout items from session
        $checkoutItems = session('checkout_items', []);
        if (empty($checkoutItems)) {
            return redirect()->route('cart')->with('error', 'Không tìm thấy sản phẩm trong giỏ hàng');
        }

        // Get products from database
        $productIds = collect($checkoutItems)->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->get();

        // Calculate total
        $total = 0;
        foreach ($products as $product) {
            $item = collect($checkoutItems)->firstWhere('product_id', $product->id);
            $total += $product->price * $item['quantity'];
        }

        // Create order
        $order = Order::create([
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'notes' => $request->notes,
            'payment_method' => $request->payment,
            'total' => $total,
            'status' => 'pending'
        ]);

        // Create order items
        foreach ($products as $product) {
            $item = collect($checkoutItems)->firstWhere('product_id', $product->id);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price
            ]);
        }

        // Clear session
        session()->forget(['checkout_items', 'order_info']);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Đơn hàng đã được tạo thành công');
    }
}
