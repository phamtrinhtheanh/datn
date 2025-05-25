<?php

namespace App\Http\Middleware;

use App\Helper\Cart;
use App\Http\Resources\CartResource;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;
use App\Models\Brand;
use App\Models\Category;
use App\Http\Resources\BrandResource;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Cache;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // Cache brands and categories for 1 hour
        $brands = Brand::select('id', 'name', 'slug')->get();
        $categories = Category::select('id', 'name', 'slug', 'tags', 'icon')->get();
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'cart' => new CartResource(Cart::getProductsAndCartItems()),
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
            'brands' => BrandResource::collection($brands),
            'categories' => CategoryResource::collection($categories),
        ];
    }
}
