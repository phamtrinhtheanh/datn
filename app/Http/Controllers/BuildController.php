<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BuildController extends Controller
{
    public function build() {
        return Inertia::render('Build', []);
    }

    public function part(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
        ]);

        $products = Product::where('category_id', $request->input('category_id'))->get();

        return response()->json($products);
    }

}
