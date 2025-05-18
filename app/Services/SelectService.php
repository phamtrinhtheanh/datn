<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class SelectService
{
    /**
     * Lấy danh sách brands đang active
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveBrands()
    {
        return Cache::remember('active_brands', 3600, function () {
            return Brand::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']);
        });
    }

    /**
     * Lấy danh sách categories đang active
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveCategories()
    {
        return Cache::remember('active_categories', 3600, function () {
            return Category::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']);
        });
    }

    /**
     * Xóa cache của brands
     */
    public function clearBrandsCache()
    {
        Cache::forget('active_brands');
    }

    /**
     * Xóa cache của categories
     */
    public function clearCategoriesCache()
    {
        Cache::forget('active_categories');
    }
}
