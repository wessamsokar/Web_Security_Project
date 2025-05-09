<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function index()
    {
        $query = Category::withCount('products');

        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        if (request('gender')) {
            $query->where('gender', request('gender'));
        }

        $categories = $query->paginate(6);  // Changed from get() to paginate()

        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $category->load('products');
        return view('categories.show', compact('category'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Women,Men,Kids & Baby,Unisex'
        ]);

        $category = Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully');
    }

    public function destroy(Category $category)
    {
        // لا نريد حذف فئة Unisex نفسها
        if ($category->gender === 'Unisex') {
            return redirect()->route('categories.index')
                ->with('error', 'Cannot delete Unisex category');
        }

        // نقل المنتجات إلى فئة Unisex
        $unisexCategory = Category::getUnisexCategory();
        $category->products()->update(['category_id' => $unisexCategory->id]);

        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted and products moved to Unisex category');
    }

    public function removeProduct(Category $category, Product $product)
    {
        $unisexCategory = Category::getUnisexCategory();
        $product->update(['category_id' => $unisexCategory->id]);

        return redirect()->back()
            ->with('success', 'Product moved to Unisex category');
    }

}
