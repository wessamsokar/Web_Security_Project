<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Product;
use App\Models\Favorite;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get the user's pending order
        $order = Order::where('user_id', $user->id)
                      ->where('status', 'pending')
                      ->first();

        $purchases = $order
            ? Purchase::with('product')->where('order_id', $order->id)->get()
            : collect(); // Empty cart

        return view('cart.index', compact('purchases'));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();

        $order = Order::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            ['total' => 0]
        );

        $order->purchases()->updateOrCreate(
            [
                'product_id' => $validated['product_id'],
                'size_id' => $validated['size_id'],
            ],
            [
                'quantity' => $validated['quantity'],
                'price' => Product::find($validated['product_id'])->price,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $purchase = Purchase::findOrFail($id);
        $purchase->quantity = $request->quantity;
        $purchase->save();

        return redirect()->route('cart.index')->with('success', 'Quantity updated.');
    }

    public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->delete();

        return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = auth()->user();

        $order = Order::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            ['total' => 0]
        );

        $purchase = Purchase::where('order_id', $order->id)
                            ->where('product_id', $request->product_id)
                            ->first();

        if ($purchase) {
            $purchase->quantity += $request->quantity;
            $purchase->save();
        } else {
            $product = Product::findOrFail($request->product_id);

            Purchase::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);
        }

        return redirect()->route('cart.index');
    }

    public function moveToFavorites($id)
    {
        $purchase = Purchase::findOrFail($id);

        if ($purchase->order->user_id !== auth()->id()) {
            abort(403);
        }

        Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $purchase->product_id,
        ]);

        $purchase->delete();

        return redirect()->route('cart.index')->with('success', 'Moved to favorites.');
    }

    public function checkout()
    {
        $user = Auth::user();

        $order = Order::where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->with('purchases.product')
                    ->firstOrFail();

        $total = $order->purchases->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return view('cart.checkout', compact('order', 'total'));
    }

    public function processCheckout(Request $request)
    {
        $user = Auth::user();

        $order = Order::where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->firstOrFail();

        // You can add payment logic here later

        $order->status = 'confirmed';
        $order->save();

        return redirect()->route('cart.index')->with('success', 'Order placed successfully!');
    }

}
