<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Add a product to the cart
    public function addToCart(Request $request)
    {
        // Validate incoming request for product and quantity
        $request->validate([
            'product' => 'required|array',
            'product.id' => 'required|integer',
            'product.name' => 'required|string',
            'product.price' => 'required|numeric',
            'product.image_url' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
        ]);

        // Extract product details and quantity
        $product = $request->input('product');
        $quantity = $request->input('quantity');

        // Retrieve existing cart from the session or create a new one
        $cart = Session::get('cart', []);

        // Check if the product already exists in the cart
        if (isset($cart[$product['id']])) {
            // Increase the quantity if it does
            $cart[$product['id']]['quantity'] += $quantity;
        } else {
            // Add new product to the cart
            $cart[$product['id']] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image_url' => $product['image_url'],
            ];
        }

        // Save the updated cart to the session
        Session::put('cart', $cart);
        return response()->json(['cart' => $cart], 201);
    }

    // Retrieve the current cart contents
    public function getCart()
    {
        // Get the cart from the session
        $cart = Session::get('cart', []);
        return response()->json(['cart' => $cart]);
    }

    // Update the quantity of a product in the cart
    public function updateCart(Request $request)
    {
        // Validate incoming request for product ID and quantity
        $request->validate([
            'id' => 'required|integer',
            'quantity' => 'required|integer|min:0',
        ]);

        // Extract the product ID and quantity
        $productId = $request->input('id');
        $quantity = $request->input('quantity');

        // Retrieve the current cart
        $cart = Session::get('cart', []);

        // Check if the product exists in the cart
        if (isset($cart[$productId])) {
            if ($quantity > 0) {
                // Update the quantity if it's greater than 0
                $cart[$productId]['quantity'] = $quantity;
            } else {
                // Remove the product if the quantity is 0
                unset($cart[$productId]);
            }
        }

        // Store the updated cart back in the session
        Session::put('cart', $cart);
        return response()->json(['cart' => $cart]);
    }

    // Remove a product from the cart
    public function removeFromCart($id)
    {
        // Retrieve the current cart from session
        $cart = Session::get('cart', []);

        // Check if the product exists and remove it
        if (isset($cart[$id])) {
            unset($cart[$id]);
        }

        // Store the updated cart back in the session
        Session::put('cart', $cart);
        return response()->json(['cart' => $cart]);
    }
}