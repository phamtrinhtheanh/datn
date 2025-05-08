<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with(['products' => function ($query) {
            $query->where('is_active', true)
                ->latest()
                ->take(10);
        }])->get();

        $brands = Brand::query()
            ->select('name', 'slug')
            ->get();

        return Inertia::render('Home', [
            'categories' => $categories,
            'brands' => $brands
        ]);
    }

    public function product($slug, $productId)
    {
        $product = Product::find($productId);
        $product->specs = json_decode($product->specs);
        return Inertia::render('Product', ['product' => $product]);
    }

    public function cart()
    {
        $user = Auth::user();
        if ($user) {
            $cart = $user->cart()->firstOrCreate([
                'user_id' => $user->id,
            ]);
            $cartItems = $cart->items()->with('product')->get();
        }

        return Inertia::render('Cart', [
            'products' => $cartItems ?? [],
        ]);
    }

    public function productByCategory($slug, $categoryId) {
        $products = Category::findOrFail($categoryId)
            ->products()
            ->with('category')
            ->paginate(5)
            ->appends(request()->query());

        return Inertia::render('ProductSearch', [
            'products' => $products,
            'category' => Category::findOrFail($categoryId),
        ]);
    }

}
