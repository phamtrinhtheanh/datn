<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(): Response
    {
        $user = auth()->user();
        if ($user) {
            $cartItems = CartItem::where('user_id', $user->id)->get();
        }

        return Inertia::render('Cart', [
            'products' => $cartItems ?? [],
        ]);
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        $cartItemIds = $request->input('cart_items');

        $deleted = CartItem::whereIn('id', $cartItemIds)
            ->whereHas('cart', fn ($q) => $q->where('user_id', $user->id))
            ->delete();

        if ($deleted === 0) {
            abort(404, 'Không tìm thấy sản phẩm trong giỏ hàng.');
        }
        $cart = $user->cart()->firstOrCreate(['user_id' => $user->id]);
        $cart->load('items.product');
        return back()->with([
            'success' => 'Thêm vào giỏ hàng thành công!',
            'cart' => $cart->fresh(['items.product'])
        ]);
    }

    public function addToCart(AddToCartRequest $request)
    {
        $user = Auth::user();

        if (!$user) {
            session(['url.intended' => url()->current()]);
            return Inertia::location(route('login'));
        }

        $cart = $user->cart()->firstOrCreate(['user_id' => $user->id]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // Kiểm tra sản phẩm đã có trong giỏ chưa
        $item = $cart->items()->where('product_id', $productId)->first();

        if ($item) {
            $item->update(['quantity' => $item->quantity + $quantity]);
        } else {
            $product = Product::findOrFail($productId);
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        // Cập nhật tổng
        $cart->load('items.product');
        $cart->update([
            'total' => $cart->items->sum(fn($item) => $item->quantity * $item->price)
        ]);

        return back()->with([
            'success' => 'Thêm vào giỏ hàng thành công!',
            'cart' => $cart->fresh(['items.product'])
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $quantity = (int) $request->input('quantity');

        $item = CartItem::where('id', $id)
            ->whereHas('cart', fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        if ($quantity <= 0) {
            $item->delete();
        } else {
            $item->update(['quantity' => $quantity]);
        }

        // Cập nhật lại tổng tiền giỏ hàng
        $item->cart->update([
            'total' => $item->cart->items->sum(fn($i) => $i->quantity * $i->price)
        ]);

        return back()->with('success', 'Cập nhật giỏ hàng thành công!');
    }

    public function miniCart()
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return [];
        }

        $cart = $user->cart()->firstOrCreate(['user_id' => $user->id]);
        $cartItems = $cart->items()->with('product')->get()->toArray();
        $items = collect($cartItems);

        $count = $items->sum('quantity');

        $total = $items->reduce(function ($carry, $item) {
            return $carry + ($item['product']['price'] * $item['quantity']);
        }, 0);

        return [
            'count' => $count,
            'total' => $total,
        ];

    }
}
