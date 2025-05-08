<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    public function getCart()
    {
        if (auth()->check()) {
            return Cart::firstOrCreate(['user_id' => auth()->id()]);
        }

        $sessionId = Session::getId();
        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    public function addItem(Product $product, int $quantity = 1): CartItem
    {
        $cart = $this->getCart();

        $cartItem = $cart->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            $cartItem = $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price
            ]);
        }

        $cart->calculateTotal();

        return $cartItem;
    }

    public function updateQuantity(CartItem $cartItem, int $quantity): void
    {
        $cartItem->quantity = $quantity;
        $cartItem->save();

        $cartItem->cart->calculateTotal();
    }

    public function removeItem(CartItem $cartItem): void
    {
        $cartItem->delete();
        $cartItem->cart->calculateTotal();
    }

    public function clearCart(): void
    {
        $cart = $this->getCart();
        $cart->items()->delete();
        $cart->total = 0;
        $cart->save();
    }
}
