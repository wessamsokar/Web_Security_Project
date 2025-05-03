<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:web')->except('list');
    }

    // Basic list method to fetch all products
    public function list(Request $request)
    {
        // Fetch all products (for demonstration purposes)
        $products = Product::all();

        // Example queries (for demonstration purposes)
        $productsLessThan20000 = Product::where('price', '<', 20000)->get();
        $productsCodeLikeTV = Product::where('code', 'like', 'tv%')->get();
        $productsOrderByPriceDesc = Product::orderBy('price', 'desc')->get();
        $productsBetween20000And40000 = Product::where('price', '>', 20000)->where('price', '<', 40000)->get();
        $productsGreaterThan40000OrLessThan20000 = Product::orWhere('price', '>', 40000)->orWhere('price', '<', 20000)->get();

        // Dynamic query building based on request parameters
        $query = Product::select("products.*");

        // Filter by keywords (name)
        $query->when($request->keywords, function ($q) use ($request) {
            return $q->where("name", "like", "%{$request->keywords}%");
        });

        // Filter by minimum price
        $query->when($request->min_price, function ($q) use ($request) {
            return $q->where("price", ">=", $request->min_price);
        });

        // Filter by maximum price
        $query->when($request->max_price, function ($q) use ($request) {
            return $q->where("price", "<=", $request->max_price);
        });

        // Sort by column and direction
        $query->when($request->order_by, function ($q) use ($request) {
            return $q->orderBy($request->order_by, $request->order_direction ?? "ASC");
        });

        // Execute the query and get the results
        $products = $query->get();

        // Pass the products to the view
        return view("products.list", compact('products'));
    }

    public function edit(Request $request, Product $product = null) 
    {
        if(!auth()->check()) return redirect()->route('login'); 

        $product = $product??new Product();
        return view("products.edit", compact('product'));
    
    }

    public function save(Request $request, Product $product = null) 
    {

        $this->validate($request, [
            'code' => ['required', 'string', 'max:32'],
            'name' => ['required', 'string', 'max:128'],
            'model' => ['required', 'string', 'max:256'],
            'description' => ['required', 'string', 'max:1024'],
            'price' => ['required', 'numeric'],
        ]);

        $product = $product??new Product();
        $product->fill($request->all());
        $product->save();

        return redirect()->route('products_list');
    }

    public function delete(Request $request, Product $product) 
    {
        if (!auth()->user()->hasPermissionTo('delete_products')) {
            abort(401);
        }

        $product->delete();
        return redirect()->route('products_list');
    }
}