<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalRevenue = Order::sum('total');

        return view('welcome', compact('totalOrders', 'totalProducts', 'totalRevenue'));
    }
}
