<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\Size;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Cart;
use App\Models\Order;

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

        // Add favorite status for each product
        if (Auth::check()) {
            $favoriteIds = Favorite::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();

            $products->getCollection()->transform(function ($product) use ($favoriteIds) {
                $product->is_favorited = in_array($product->id, $favoriteIds);
                return $product;
            });
        }

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

    public function favorites()
    {
        $favorites = Favorite::with('product')->where('user_id', Auth::id())->get();
        return view('favorites.index', compact('favorites'));
    }

    public function toggleFavorite(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $favorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorited = false;
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ]);
            $isFavorited = true;
        }

        return redirect()->back()->with('success', $isFavorited ? 'Added to favorites' : 'Removed from favorites');
    }

    public function removeFavorite($product_id)
    {
        Favorite::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->delete();

        return redirect()->route('favorites.index')->with('success', 'Removed from favorites.');
    }

    // Cart methods moved from CartController
    public function cartIndex()
    {
        $user = Auth::user();

        $cartItems = Cart::with(['product.productSizes.size', 'size'])
            ->where('user_id', $user->id)
            ->get();

        return view('cart.index', compact('cartItems'));
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();

        Cart::updateOrCreate(
            [
                'user_id' => $user->id,
                'product_id' => $validated['product_id'],
                'size_id' => $validated['size_id'],
            ],
            [
                'quantity' => $validated['quantity'],
            ]
        );

        return redirect()->route('products.cartIndex')->with('success', 'Product added to cart!');
    }

    public function updateCartItem(Request $request, Cart $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return redirect()->route('products.cartIndex')->with('success', 'Quantity updated.');
    }

    public function removeCartItem(Cart $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()->route('products.cartIndex')->with('success', 'Item removed from cart.');
    }

    public function moveToFavoritesFromCart(Cart $cartItem)
    {
        if ($cartItem->user_id !== auth()->id()) {
            abort(403);
        }

        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $cartItem->product_id,
        ]);

        $cartItem->delete();

        return redirect()->route('products.cartIndex')->with('success', 'Moved to favorites.');
    }

    // Checkout methods moved from CartController
    public function checkout()
    {
        $user = Auth::user();
        $cartItems = Cart::with(['product', 'size'])
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products.cartIndex')
                ->with('error', 'Your cart is empty');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return view('cart.checkout', compact('cartItems', 'total'));
    }

    public function processCheckout(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::with(['product', 'size'])
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('products.cartIndex')
                ->with('error', 'Your cart is empty');
        }

        // Calculate total
        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        $orderStatus = 'pending payment'; // Default status
        $message = 'Order placed successfully. Waiting for payment confirmation.';

        // Check if user has enough credit
        // Assuming 'credit' is a column on the User model
        if ($user->credit < $total) {
            $orderStatus = 'Reject'; // Set status to Reject if insufficient credit
            $message = 'Order rejected due to insufficient credit.';
        }

        // Create order with the determined status
        $order = Order::create([
            'user_id' => $user->id,
            'status' => $orderStatus,
            'total' => $total
        ]);

        // Create purchases for each cart item
        foreach ($cartItems as $item) {
            $order->purchases()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'size_id' => $item->size_id
            ]);

            // Note: Stock is NOT updated here. It will be updated when the order is 'Accepted'.
        }

        // Clear the cart
        $cartItems->each->delete();

        return redirect()->route('orders.view') // Redirect to orders view
            ->with('success', $message);
    }
}
