<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function product($text, $id)
    {
        $product = Product::find($id);
        return Inertia::render('Product', ['product' => $product]);
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
