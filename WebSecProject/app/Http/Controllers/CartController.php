<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size_id' => 'required|exists:sizes,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = auth()->id();

        $existingItem = Cart::where('user_id', $userId)
            ->where('product_id', $request->product_id)
            ->where('size_id', $request->size_id)
            ->first();

        if ($existingItem) {
            $existingItem->quantity += $request->quantity;
            $existingItem->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $request->product_id,
                'size_id' => $request->size_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json(['success' => true]);
    }


    public function destroy($id)
    {
        $cart = Cart::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $cart->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }
}
