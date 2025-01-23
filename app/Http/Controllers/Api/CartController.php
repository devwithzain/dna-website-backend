<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('services')->where('user_id', Auth::id())->get();
        if ($cart->isEmpty()) {
            return response()->json(['message' => 'Cart is empty.'], 404);
        }
        return response()->json($cart);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
        ]);

        $cart = Cart::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'service_id' => $validated['service_id'],
            ],
        );
        return response()->json(['cart' => $cart, 'success' => "Item added to cart."], 201);
    }
    public function destroy($id)
    {
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())->first();

        if ($cart) {
            $cart->delete();
            return response()->json(['success' => 'Service removed from cart.']);
        }
        return response()->json(['message' => 'Service not found.'], 404);
    }
    public function deleteAll()
    {
        Cart::where('user_id', Auth::id())->delete();
        return response()->json(['success' => 'All services removed from cart.']);
    }
}