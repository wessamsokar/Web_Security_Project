<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\Favorite;
use App\Models\Cart;
use App\Models\Purchase;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $totalSpent = Purchase::whereHas('order', function ($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->where('status', 'Accept');
        })->get()->sum(function ($purchase) {
            return $purchase->quantity * $purchase->price;
        });

        return view('welcome', [
            'ordersCount'     => Order::where('user_id', $userId)->count(),
            'favoritesCount'  => Favorite::where('user_id', $userId)->count(),
            'cartCount'       => Cart::where('user_id', $userId)->sum('quantity'),
            'totalSpent'      => $totalSpent,
            'recentOrders'    => Order::where('user_id', $userId)->latest()->take(5)->get(),
            'recentPurchases' => Purchase::whereHas('order', fn($q) =>
                $q->where('user_id', $userId)->where('status', 'pending')
            )->with('product')->latest()->take(5)->get(),
        ]);
    }
}
