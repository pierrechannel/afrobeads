<?php

namespace App\Http\Controllers\admin\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = Cart::with(['variant.product.images'])
            ->where('user_id', auth()->id())
            ->get();

        $total = $cart->sum(function($item) {
            return $item->variant->getFinalPriceAttribute() * $item->quantity;
        });

        return response()->json([
            'cart_items' => $cart,
            'total' => $total
        ]);
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'variant_id' => $validated['variant_id']
            ],
            [
                'quantity' => \DB::raw('quantity + ' . $validated['quantity'])
            ]
        );

        return response()->json(['cart_item' => $cart->load('variant.product')]);
    }

    public function updateQuantity(Request $request, Cart $cart)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->update($validated);
        return response()->json(['cart_item' => $cart->load('variant.product')]);
    }

    public function destroy(Cart $cart)
    {
        $cart->delete();
        return response()->json([], 204);
    }
}

