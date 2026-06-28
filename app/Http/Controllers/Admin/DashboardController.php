<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $revenue = Order::sum('total');
        $recentOrders = Order::latest()->take(5)->get();
        $latestProducts = Product::with('category')->latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalProducts', 'totalCategories',
            'totalOrders', 'pendingOrders', 'completedOrders', 'revenue',
            'recentOrders', 'latestProducts'
        ));
    }

    public function orders()
    {
        $orders = Order::with('user')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function users()
    {
        $users = User::where('role', 'customer')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }
}
