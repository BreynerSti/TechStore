<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Estadísticas generales
        $stats = [
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_users' => User::count(),
            'total_categories' => Category::count(),
        ];

        // Estadísticas de pedidos por estado (agrupadas)
        $orderStats = [
            'pending' => Order::where('status', 'pending')->count(),
            'paid' => Order::whereIn('status', ['processing', 'paid'])->count(),
            'shipped' => Order::whereIn('status', ['shipped', 'delivered'])->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        // Ingresos del mes actual
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total');

        // Últimos pedidos (5 más recientes)
        $recentOrders = Order::with(['user'])
            ->latest()
            ->take(5)
            ->get();

        // Productos más vendidos (basado en order_items)
        $topProducts = Product::withCount(['orderItems as total_sold' => function($query) {
                $query->select(DB::raw('SUM(quantity)'));
            }])
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        // Usuarios nuevos este mes
        $newUsersThisMonth = User::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        return view('admin.dashboard', compact(
            'stats',
            'orderStats',
            'monthlyRevenue',
            'recentOrders',
            'topProducts',
            'newUsersThisMonth'
        ));
    }
} 