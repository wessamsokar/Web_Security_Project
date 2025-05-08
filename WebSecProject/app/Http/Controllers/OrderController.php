<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders.index');
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
    {
        // Validate and store order
        return redirect()->route('orders.index')->with('success', 'Order created successfully');
    }

    public function show($id)
    {
        return view('orders.show');
    }

    public function edit($id)
    {
        return view('orders.edit');
    }

    public function update(Request $request, $id)
    {
        // Validate and update order
        return redirect()->route('orders.index')->with('success', 'Order updated successfully');
    }

    public function destroy($id)
    {
        // Delete order
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully');
    }
}
