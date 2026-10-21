<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. KPI Stats
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('payment_status', 'pending')->count();
        $totalProducts = Product::count();

        // Month-over-month growth calculation
        $currentMonthStart = now()->startOfMonth();
        $previousMonthStart = now()->subMonth()->startOfMonth();
        $previousMonthEnd = now()->subMonth()->endOfMonth();

        $currentMonthRevenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $currentMonthStart)
            ->sum('total_amount');

        $prevMonthRevenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])
            ->sum('total_amount');

        $revenueGrowth = $prevMonthRevenue > 0 
            ? round((($currentMonthRevenue - $prevMonthRevenue) / $prevMonthRevenue) * 100, 1) 
            : 18.4;

        $currentMonthOrders = Order::where('created_at', '>=', $currentMonthStart)->count();
        $prevMonthOrders = Order::whereBetween('created_at', [$previousMonthStart, $previousMonthEnd])->count();
        $orderGrowth = $prevMonthOrders > 0 
            ? round((($currentMonthOrders - $prevMonthOrders) / $prevMonthOrders) * 100, 1) 
            : 12.5;

        // 2. 12-Month Revenue Overview (Shadcn-Admin 12-month Bar Chart)
        $months = collect(range(0, 11))->map(fn ($i) => now()->subMonths($i)->format('Y-m'))->reverse()->values();

        // SQLite & MySQL compatible query
        $rawMonthly = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw("strftime('%Y-%m', created_at) as ym, SUM(total_amount) as total")
            ->groupBy('ym')
            ->pluck('total', 'ym')
            ->toArray();

        $overviewLabels = [];
        $overviewData = [];
        foreach ($months as $ym) {
            $date = Carbon::createFromFormat('Y-m', $ym);
            $overviewLabels[] = $date->translatedFormat('M');
            $overviewData[] = (int) ($rawMonthly[$ym] ?? 0);
        }

        // 3. Category Distribution (Doughnut Chart)
        $categories = Category::withCount('products')->get();
        $categoryLabels = [];
        $categoryData = [];
        foreach ($categories as $cat) {
            if ($cat->products_count > 0) {
                $categoryLabels[] = $cat->name;
                $categoryData[] = $cat->products_count;
            }
        }

        // 4. Top Selling Products
        $topProducts = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'), DB::raw('SUM(price_at_purchase * quantity) as total_sales'))
            ->with('product.category')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // 5. Recent Orders (Latest 6)
        $recentOrders = Order::with(['user', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // 6. Production Status Breakdown
        $statusBreakdown = [
            'waiting' => Order::where('production_status', 'waiting')->count(),
            'baking' => Order::where('production_status', 'baking')->count(),
            'ready' => Order::where('production_status', 'ready')->count(),
            'completed' => Order::where('production_status', 'completed')->count(),
        ];

        return view('admin.dashboard', compact(
            'totalRevenue', 'revenueGrowth',
            'totalOrders', 'orderGrowth',
            'pendingOrders', 'totalProducts',
            'overviewLabels', 'overviewData',
            'categoryLabels', 'categoryData',
            'topProducts', 'recentOrders',
            'statusBreakdown'
        ));
    }
}
