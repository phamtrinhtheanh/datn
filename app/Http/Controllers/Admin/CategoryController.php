<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->input('sort_field', 'name');
        $sortDirection = $request->input('sort_direction', 'asc');

        // Validate sort field
        $allowedSortFields = ['name', 'products_count', 'created_at'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'name';
        }

        // Validate sort direction
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $categories = Category::withCount('products')
            ->orderBy($sortField, $sortDirection)
            ->get();

        $categories->transform(function ($category) {
            $category->tags = json_decode($category->tags, true);
            return $category;
        });

        return Inertia::render('Admin/Categories/Index', [
            'categories' => [
                'data' => $categories,
                'total' => $categories->count()
            ],
            'filters' => [
                'sort_field' => $sortField,
                'sort_direction' => $sortDirection
            ]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tags' => 'nullable|array',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['tags'] = json_encode($validated['tags'] ?? []);

        Category::create($validated);

        return redirect()->back();
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tags' => 'nullable|array',
        ]);
        
        $validated['slug'] = Str::slug($validated['name']);
        $validated['tags'] = json_encode($validated['tags'] ?? []);

        $category->update($validated);

        return redirect()->back();
    }

    public function destroy(Category $category)
    {
        if ($category->products_count > 0) {
            return back()->with('error', 'Không thể xóa danh mục đang có sản phẩm.');
        }
        
        $category->delete();
        return redirect()->back();
    }
}
