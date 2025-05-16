<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\Size;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'productSizes'])
            ->select('products.*')
            // Add total_stock as a subquery
            ->selectRaw('(SELECT COALESCE(SUM(quantity), 0) FROM product_size WHERE product_size.product_id = products.id) as total_stock')
            // Order by stock availability first, then by total stock amount descending
            ->orderByRaw('(SELECT COALESCE(SUM(quantity), 0) FROM product_size WHERE product_size.product_id = products.id) > 0 DESC')
            ->orderByRaw('(SELECT COALESCE(SUM(quantity), 0) FROM product_size WHERE product_size.product_id = products.id) DESC');

        // Filter by gender from category
        if ($request->filled('gender')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('gender', $request->gender);
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Get categories based on gender
        $categoriesQuery = Category::query();
        if ($request->filled('gender')) {
            $categoriesQuery->where('gender', $request->gender);
        }
        $categories = $categoriesQuery->get();

        $products = $query->paginate(12);

        return view('products.index', compact('products', 'categories'));
    }

    // In app/Http/Controllers/ProductController.php
    public function edit(Product $product)
    {
        $product->load('productSizes'); // Eager load the relationship
        $categories = Category::all();
        $sizes = Size::all();

        return view('products.edit', compact('product', 'categories', 'sizes'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sizes' => 'array',
            'sizes.*' => 'nullable|numeric|min:0'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $totalStock = array_sum($request->sizes ?? []);
        $validated['stock'] = $totalStock;

        $product = Product::create($validated);

        if ($request->has('sizes')) {
            foreach ($request->sizes as $sizeId => $quantity) {
                if ($quantity > 0) {
                    $product->productSizes()->create([
                        'size_id' => $sizeId,
                        'quantity' => $quantity
                    ]);
                }
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'nullable|string',
            'sizes' => 'array',
            'sizes.*' => 'nullable|numeric|min:0'
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        if ($request->has('sizes')) {
            $product->productSizes()->delete();

            $totalStock = 0;
            foreach ($request->sizes as $sizeId => $quantity) {
                $quantity = (int) $quantity;
                if ($quantity > 0) {
                    $product->productSizes()->create([
                        'size_id' => $sizeId,
                        'quantity' => $quantity
                    ]);
                    $totalStock += $quantity;
                }
            }
            $validated['stock'] = $totalStock;
        }

        $product->update($validated);

        return redirect()->route('products.edit', $product)
            ->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Delete related product sizes first
        $product->productSizes()->delete();

        // Delete the image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }

    // Keep only this show() method with proper implementation
    public function show(Product $product)
    {
        $totalStock = $product->productSizes->sum('quantity');
        $sizes = Size::all();

        $isFavorited = false;
        if (Auth::check()) {
            $isFavorited = Favorite::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->exists();
        }

        return view('products.show', compact('product', 'totalStock', 'sizes', 'isFavorited'));
    }

}
