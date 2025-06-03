<?php

namespace App\Http\Controllers;

use App\Helper\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function view(Request $request)
    {
        $user = $request->user();

        // Giỏ hàng của user đã đăng nhập
        if ($user) {
            Cart::moveCartItemsIntoDb();
            $cartItems = CartItem::where('user_id', $user->id)
                ->get();

            $products = $cartItems->map(function ($item) {
                $productData = $item->product->toArray();
                $productData['quantity'] = $item->quantity;
                if (isset($productData['images']) && is_string($productData['images'])) {
                    $productData['images'] = json_decode($productData['images'], true);
                }
                $productData['images'] = $productData['images'] ?? [];
                $productData['cart_item_id'] = $item->id;
                return $productData;
            });

            $total = $products->sum(function ($product) {
                return $product['price'] * $product['quantity'];
            });
            return Inertia::render('Cart', [
                'cart' => [
                    'data' => [
                        'items' => $cartItems->toArray(),
                        'products' => $products->values()->toArray(),
                        'total' => $total,
                        'count' => $products->sum('quantity')
                    ]
                ]
            ]);
        } // Giỏ hàng khách chưa đăng nhập
        else {
            $cartData = Cart::getProductsAndCartItems();
            $products = $cartData[0]->map(function ($product) use ($cartData) {
                $cartItem = $cartData[1]->get($product->id);
                $productData = $product->toArray();
                $productData['quantity'] = $cartItem['quantity'] ?? 0;

                if (isset($productData['images']) && is_string($productData['images'])) {
                    $productData['images'] = json_decode($productData['images'], true);
                }
                $productData['images'] = $productData['images'] ?? [];
                $productData['cart_item_id'] = $product->id;
                return $productData;
            });

            $total = $products->sum(function ($product) {
                return $product['price'] * $product['quantity'];
            });

            return Inertia::render('Cart', [
                'cart' => [
                    'data' => [
                        'items' => $cartData[1]->values()->toArray(),
                        'products' => $products->values()->toArray(),
                        'total' => $total,
                        'count' => $products->sum('quantity')
                    ]
                ]
            ]);
        }
    }

    public function store(Request $request, Product $product)
    {
        $quantity = $request->post('quantity', 1);
        $user = $request->user();

        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) {
                $cartItem->increment('quantity', $quantity);
            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                ]);
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            $isProductExists = false;
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] += $quantity;
                    $isProductExists = true;
                    break;
                }
            }

            if (!$isProductExists) {
                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ];
            }
            Cart::setCookieCartItems($cartItems);
        }

        return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

    public function update(Request $request, $product)
    {
        $quantity = $request->integer('quantity');
        $user = $request->user();

        if ($quantity < 1) {
            return response()->json(['message' => 'Số lượng phải lớn hơn 0'], 422);
        }

        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product])->firstOrFail();
            $cartItem->update(['quantity' => $quantity]);
        } else {
            $cartItems = Cart::getCookieCartItems();
            $updated = false;
            foreach ($cartItems as &$item) {
                if ($item['product_id'] == $product) {
                    $item['quantity'] = $quantity;
                    $updated = true;
                    break;
                }
            }
            if ($updated) {
                Cart::setCookieCartItems($cartItems);
            }
        }

        return redirect()->back();
    }

    public function delete(Request $request, $product)
    {
        $user = $request->user();
        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product])->firstOrFail();
            $cartItem->delete();

        } else {
            $cartItems = Cart::getCookieCartItems();
            $removed = false;
            foreach ($cartItems as $index => $item) {
                if ($item['product_id'] == $product) {
                    array_splice($cartItems, $index, 1);
                    $removed = true;
                    break;
                }
            }
            if ($removed) {
                Cart::setCookieCartItems($cartItems);
            }
        }

        return redirect()->back()->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
    }
}

