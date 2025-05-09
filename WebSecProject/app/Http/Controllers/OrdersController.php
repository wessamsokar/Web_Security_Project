<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'purchases.product');

        if ($request->search) {
            $query->where('id', 'LIKE', "%{$request->search}%")
                ->orWhereHas('user', function ($q) use ($request) {
                    $q->where('name', 'LIKE', "%{$request->search}%");
                });
        }

        if ($request->status && $request->status != 'All Status') {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);

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
}
