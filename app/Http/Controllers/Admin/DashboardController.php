<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        $endDate = Carbon::now();

        // Thống kê doanh thu
        $revenueStats = $this->getRevenueStats($startDate, $endDate, $period);

        // Thống kê đơn hàng
        $orderStats = $this->getOrderStats($startDate, $endDate, $period);

        // Sản phẩm bán chạy
        $topProducts = $this->getTopProducts($startDate, $endDate);

        // Thống kê tồn kho
        $inventoryStats = $this->getInventoryStats();

        // Thống kê khuyến mãi
        $promotionStats = $this->getPromotionStats();

        return Inertia::render('Admin/Dashboard/Index', [
            'revenueStats' => $revenueStats,
            'orderStats' => $orderStats,
            'topProducts' => $topProducts,
            'inventoryStats' => $inventoryStats,
            'promotionStats' => $promotionStats,
        ]);
    }

    private function getRevenueStats($startDate, $endDate, $period)
    {
        $total = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total');

        $byDay = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('d/m/Y'),
                    'total' => $item->total
                ];
            });

        return [
            'total' => $total,
            'by_day' => $byDay,
            'period' => $period
        ];
    }

    private function getOrderStats($startDate, $endDate, $period)
    {
        $total = Order::whereBetween('created_at', [$startDate, $endDate])->count();
        $completed = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $pending = Order::where('status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
        $cancelled = Order::where('status', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $byDay = Order::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('d/m/Y'),
                    'total' => $item->total
                ];
            });

        return [
            'total' => $total,
            'completed' => $completed,
            'pending' => $pending,
            'cancelled' => $cancelled,
            'by_day' => $byDay,
            'period' => $period
        ];
    }

    private function getTopProducts($startDate, $endDate)
    {
        return Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->with('items.product')
            ->get()
            ->flatMap(function ($order) {
                return $order->items;
            })
            ->groupBy('product_id')
            ->map(function ($items) {
                $product = $items->first()->product;
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'total_sold' => $items->sum('quantity'),
                    'total_revenue' => $items->sum(function ($item) {
                        return $item->quantity * $item->price;
                    })
                ];
            })
            ->sortByDesc('total_revenue')
            ->take(5)
            ->values();
    }

    private function getInventoryStats()
    {
        $totalProducts = Product::count();
        $lowStock = Product::where('stock', '<', 10)->count();
        $outOfStock = Product::where('stock', 0)->count();

        $byCategory = Category::withCount('products')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'products_count' => $category->products_count
                ];
            });

        return [
            'total_products' => $totalProducts,
            'low_stock' => $lowStock,
            'out_of_stock' => $outOfStock,
            'by_category' => $byCategory
        ];
    }

    private function getPromotionStats()
    {
        $activePromotions = Promotion::where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->count();

        $totalDiscount = 0;

        $byType = Promotion::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->map(function ($item) {
                return [
                    'type' => $item->type,
                    'count' => $item->count
                ];
            });

        return [
            'active_promotions' => $activePromotions,
            'total_discount' => $totalDiscount,
            'by_type' => $byType
        ];
    }

    private function getStartDate($period)
    {
        return match($period) {
            'week' => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            'year' => Carbon::now()->startOfYear(),
            default => Carbon::now()->startOfMonth(),
        };
    }
}
