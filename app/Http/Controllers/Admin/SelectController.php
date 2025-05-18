<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Services\SelectService;
use Illuminate\Http\JsonResponse;

class SelectController extends Controller
{
    protected $selectService;

    public function __construct(SelectService $selectService)
    {
        $this->selectService = $selectService;
    }

    /**
     * Lấy danh sách brands cho select box
     */
    public function brands(): JsonResponse
    {
        return response()->json(Brand::all());
    }

    /**
     * Lấy danh sách categories cho select box
     */
    public function categories(): JsonResponse
    {
        $categories = Category::all();

        // Giải mã trường 'tags' nếu nó là chuỗi JSON
        $categories->each(function($category) {
            if (isset($category->tags) && is_string($category->tags)) {
                $category->tags = json_decode($category->tags, true); // true để giải mã thành mảng
            }
        });

        return response()->json($categories);
    }
}
