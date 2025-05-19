<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'Pending') {
                $query->whereNotIn('status', ['Accept', 'Reject']);
            } else {
                $query->where('status', $request->status);
            }
        }

        // Date range filter
        if ($request->filled('date_range')) {
            switch ($request->date_range) {
                case 'last_7_days':
                    $query->whereDate('created_at', '>=', now()->subDays(7));
                    break;
                case 'last_30_days':
                    $query->whereDate('created_at', '>=', now()->subDays(30));
                    break;
                case 'this_month':
                    $query->whereYear('created_at', now()->year)
                        ->whereMonth('created_at', now()->month);
                    break;
            }
        }

        $orders = $query->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $order->update($request->validate([
            'status' => 'required|string',
            'total' => 'required|numeric'
        ]));

        return redirect()->route('orders.index');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $order->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Order status updated successfully');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully');
    }

    public function view()
    {
        $user = Auth::user();
        $orders = Order::with('purchases.product')
                    ->where('user_id', $user->id)
                    ->where('status', '!=', 'pending') // exclude cart
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('orders.view', compact('orders'));
    }
}
