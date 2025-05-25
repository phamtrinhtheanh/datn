<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch the 5 latest products
        $newArrivals = Product::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('Home', [
            'newArrivals' => $newArrivals,
            // Other shared props (like categories, brands, etc.) should also be passed here if they are not globally shared
        ]);
    }
}
