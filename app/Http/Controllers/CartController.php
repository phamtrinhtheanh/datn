<?php

namespace App\Http\Controllers;

use App\Helper\Cart;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function view(Request $request)
    {
        $user = $request->user();

        if ($user) {
            // For logged-in users, fetch cart items with product data
            $cartItems = CartItem::where('user_id', $user->id)
                ->get();

            $products = $cartItems->map(function($item) {
                // Flatten product data and add quantity
                $productData = $item->product->toArray();
                $productData['quantity'] = $item->quantity;
                // Ensure images are an array of paths
                if (isset($productData['images']) && is_string($productData['images'])) {
                     $productData['images'] = json_decode($productData['images'], true);
                }
                 $productData['images'] = $productData['images'] ?? [];
                 // Add cart item ID as 'id' for frontend reference
                 $productData['cart_item_id'] = $item->id;
                return $productData;
            });

            $total = $products->sum(function($product) {
                return $product['price'] * $product['quantity'];
            });

        } else {
            // For guests, get cart data from cookie helper
            $cartData = Cart::getProductsAndCartItems();
            $products = $cartData['products']; // This should already be a collection with product data

             $products = $products->map(function($product) {
                $cartItem = collect($cartData['cartItems'])->firstWhere('product_id', $product->id);
                 $productData = $product->toArray();
                 $productData['quantity'] = $cartItem['quantity'] ?? 0;

                 if (isset($productData['images']) && is_string($productData['images'])) {
                     $productData['images'] = json_decode($productData['images'], true);
                 }
                 $productData['images'] = $productData['images'] ?? [];
                  // No distinct cart item ID for guest items unless stored in cookie
                 $productData['cart_item_id'] = $product->id; // Use product ID as a temporary key
                return $productData;
            });

            $total = $products->sum(function($product) {
                return $product['price'] * $product['quantity'];
            });

        }

        // Pass data with consistent structure
        return Inertia::render('Cart', [
            'cart' => [
                'data' => [
                    'items' => $user ? $cartItems : collect($cartData['cartItems']), // Pass raw cart items for internal logic if needed
                    'products' => $products->values()->toArray(), // Pass flattened product data with quantity for the table
                    'total' => $total,
                    'count' => $products->sum('quantity')
                ]
            ],
            // You might want to pass the user's default address here if it exists
            // 'userAddress' => $user && $user->address ? $user->address : null,
        ]);
    }

    // Assuming you have store, update, delete methods correctly implemented as per previous code
    // Make sure update and delete use the cart item ID, not product ID, if implemented for logged-in users

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
                    'user_id' => null, // Keep null for guest items
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    // Maybe add product details like name, image, etc. here for easier retrieval on frontend
                ];
            }
            Cart::setCookieCartItems($cartItems);
        }

        return redirect()->back()->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

     // Update quantity using cart item ID (for logged in) or product ID (for guest/cookie)
    public function update(Request $request, $product)
    {
        $quantity = $request->integer('quantity');
        $user = $request->user();

        if ($quantity < 1) {
            return response()->json(['message' => 'Số lượng phải lớn hơn 0'], 422);
        }

        if ($user) {
            // Find by product ID for logged in user
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product])->firstOrFail();
            $cartItem->update(['quantity' => $quantity]);
        } else {
            // Find by product ID in cookie items for guest
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

    // Delete item using cart item ID (for logged in) or product ID (for guest/cookie)
    public function delete(Request $request, $cartItemId)
    {
        $user = $request->user();

        if ($user) {
             // Find and delete by cart item ID for logged in user
            $cartItem = CartItem::where('user_id', $user->id)->findOrFail($cartItemId);
            $cartItem->delete();
        } else {
             // Find and remove by product ID in cookie items for guest
            $cartItems = Cart::getCookieCartItems();
            $removed = false;
             foreach ($cartItems as $index => $item) {
                // Use product_id for lookup in cookie cart
                if ($item['product_id'] == $cartItemId) {
                    array_splice($cartItems, $index, 1);
                    $removed = true;
                    break;
                }
            }
            if ($removed) {
                 Cart::setCookieCartItems($cartItems);
            }
        }

         return redirect()->back(303); // Use 303 See Other to force GET request after DELETE
    }

     // Assuming you have a checkout method
    public function checkout(Request $request)
    {
        // Logic for checkout...
        dd($request->all());
         // return Inertia::render('Checkout', [...]);
    }
}

