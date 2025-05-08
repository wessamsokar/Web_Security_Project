<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        // Validate and store product
        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function show($id)
    {
        return view('products.show');
    }

    public function edit($id)
    {
        return view('products.edit');
    }

    public function update(Request $request, $id)
    {
        // Validate and update product
        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        // Delete product
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
