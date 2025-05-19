<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoritesController extends Controller
{
    public function index()
    {
        $favorites = Favorite::with('product')->where('user_id', Auth::id())->get();
        return view('favorites.index', compact('favorites'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json(['message' => 'Added to favorites.']);
    }

    public function destroy($product_id)
    {
        Favorite::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->delete();

        return redirect()->route('favorites.index')->with('success', 'Removed from favorites.');
    }
}
