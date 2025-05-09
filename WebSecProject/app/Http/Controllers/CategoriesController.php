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
        $query = $category->products();

        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        if (request('search_id')) {
            $query->where('id', 'like', '%' . request('search_id') . '%');
        }

        $products = $query->get();
        $category->setRelation('products', $products);

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

    public function update(Request $request, Category $category)
    {
        // Check if this is the first Unisex category
        if ($category->gender === 'Unisex' && Category::where('gender', 'Unisex')->orderBy('id')->first()->id === $category->id) {
            return redirect()->route('categories.index')
                ->with('error', 'The default Unisex category cannot be modified.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:Men,Women,Kids & Baby,Unisex',
        ]);

        $category->update([
            'name' => $request->name,
            'gender' => $request->gender,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully');
    }

    public function destroy(Category $category)
    {
        // Check if this is the first Unisex category
        if ($category->gender === 'Unisex' && Category::where('gender', 'Unisex')->orderBy('id')->first()->id === $category->id) {
            return redirect()->route('categories.index')
                ->with('error', 'The default Unisex category cannot be deleted.');
        }

        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully');
    }

    public function removeProduct(Category $category, Product $product)
    {
        $unisexCategory = Category::getUnisexCategory();
        $product->update(['category_id' => $unisexCategory->id]);

        return redirect()->back()
            ->with('success', 'Product moved to Unisex category');
    }

}
