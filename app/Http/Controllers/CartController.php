<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = $request->input('product'); // Get the product data (id, name, price, etc.)
        $quantity = $request->input('quantity');

        $cart = Session::get('cart', []);

        // Check if the product already exists in the cart
        if (isset($cart[$product['id']])) {
            $cart[$product['id']]['quantity'] += $quantity; // Update quantity
        } else {
            $cart[$product['id']] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image_url' => $product['image_url']
            ];
        }

        Session::put('cart', $cart); // Save the cart in session
        return response()->json(['cart' => $cart]);
    }

    public function getCart()
    {
        $cart = Session::get('cart', []);
        return response()->json($cart);
    }

    public function updateCart(Request $request)
    {
        $productId = $request->input('id');
        $quantity = $request->input('quantity');

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            if ($quantity > 0) {
                $cart[$productId]['quantity'] = $quantity; // Update quantity
            } else {
                unset($cart[$productId]); // Remove item if quantity is 0
            }
        }

        Session::put('cart', $cart);
        return response()->json(['cart' => $cart]);
    }

    public function removeFromCart($id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]); // Remove item from cart
        }

        Session::put('cart', $cart);
        return response()->json(['cart' => $cart]);
    }
}