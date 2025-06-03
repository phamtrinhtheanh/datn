<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        session(['checkout_items' => $request->items]);

        return redirect()->route('checkout');
    }
    public function index()
    {
        $checkoutItems = session('checkout_items', []);

        if (empty($checkoutItems)) {
            return redirect()->route('cart')->with('error', 'Vui lòng chọn sản phẩm để thanh toán');
        }
        $productIds = collect($checkoutItems)->pluck('product_id');
        $products = Product::whereIn('id', $productIds)->get();

        $selectedProducts = $products->map(function($product) use ($checkoutItems) {
            $item = collect($checkoutItems)->firstWhere('product_id', $product->id);
            return [
                'id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'images' => $product->images,
                    'price' => $product->price
                ]
            ];
        });

        $total = $selectedProducts->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
        $promotions = Promotion::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->where('min_order_amount', '<=', $total)
            ->get()
            ->toArray();

        return Inertia::render('Checkout', [
            'products' => $selectedProducts,
            'total' => $total,
            'activePromotions' => $promotions
        ]);
    }
}
