<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    public function search(Request $request) {
        $request->validate([
            'query' => 'required|string',
            'sort_by' => 'nullable|string',
            'available' => 'nullable|boolean',
            'brands' => 'nullable|array',
            'brands.*' => 'nullable|string',
            'categories' => 'nullable|array',
            'categories.*' => 'nullable|string',
        ]);

        $query = $request->input('query');
        $sortBy = $request->input('sort_by');
        $productsQuery = Product::search($query);

        if ($sortBy) {
            // Sắp xếp theo giá (price)
            if ($sortBy == 'price_asc') {
                $productsQuery = $productsQuery->orderBy('price', 'asc');
            } elseif ($sortBy == 'price_desc') {
                $productsQuery = $productsQuery->orderBy('price', 'desc');
            }
        }

        $products = $productsQuery->paginate(5);

        return Inertia::render('ProductSearch', [
            'products' => $products,
            'query' => $query,
            'sort_by' => $request->input('sort_by'),
        ]);

    }
}
