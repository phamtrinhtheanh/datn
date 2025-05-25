<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        // Validate sort field
        $allowedSortFields = ['created_at', 'price', 'sold', 'stock', 'rating'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'created_at';
        }

        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $query->orderBy($sortField, $sortDirection);

        $perPage = $request->input('per_page', 10);
        $products = $query->paginate($perPage)
            ->withQueryString();

        return Inertia::render('Admin/Products/ProductList', [
            'products' => $products,
            'filters' => $request->only(['search', 'per_page', 'sort_field', 'sort_direction']),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Products/ProductCreate');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'import_price' => 'nullable|numeric|min:0',
            'line_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'images' => 'nullable|array|min:1|max:4',
            'main_image_index' => 'nullable|integer|min:0',
            'specs' => 'nullable|json',
            'is_featured' => 'required|boolean',
            'is_active' => 'required|boolean',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Create product with image URLs
        $validated['images'] = json_encode($validated['images'] ?? []);
        $validated['specs'] = json_decode($validated['specs'], true);
        $validated['slug'] = Str::slug($validated['name']);

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return Inertia::render('Admin/Products/ProductDetail', [
            'product' => $product->load(['brand', 'category']),
        ]);
    }

    public function edit(Product $product)
    {
        return Inertia::render('Admin/Products/ProductEdit', [
            'product' => $product->load(['brand', 'category'])
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'import_price' => 'nullable|numeric|min:0',
            'line_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'images' => 'nullable|array|min:1|max:4',
            'main_image_index' => 'nullable|integer|min:0',
            'specs' => 'nullable|json',
            'is_featured' => 'required|boolean',
            'is_active' => 'required|boolean',
            'brand_id' => 'required|exists:brands,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Create product with image URLs
        $validated['images'] = json_encode($validated['images'] ?? []);
        $validated['specs'] = json_decode($validated['specs'], true);
        $validated['slug'] = Str::slug($validated['name']);



        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function uploadImages(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|max:5120' // 5MB max
        ]);

        $uploadedImages = [];
        foreach ($request->file('images') as $image) {
            $path = $image->store('products', 'public');
            $uploadedImages[] = [
                'url' => Storage::url($path)
            ];
        }

        return response()->json([
            'success' => true,
            'images' => $uploadedImages
        ]);
    }
}
