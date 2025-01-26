<?php

namespace App\Http\Controllers\admin\api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CartController extends Controller {
    public function index(Request $request) {
        try {
            $cart = $this->getCartItems($request);
            $total = $cart->sum(function($item) {
                return $item->variant->getFinalPriceAttribute() * $item->quantity;
            });

            return response()->json([
                'cart_items' => $cart,
                'total' => $total
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve cart items ' . $e->getMessage()], 500);
        }
    }

    private function getCartItems(Request $request) {
        // Check if user is authenticated
        if (Auth::check()) {
            return Cart::with(['variant.product.images'])
                ->where('user_id', Auth::id())
                ->get();
        }

        // For guest, use session-based cart
        $cartItems = $request->session()->get('guest_cart', []);
        return collect($cartItems);
    }

    public function addToCart(Request $request) {
        try {
            $validated = $request->validate([
                'variant_id' => 'required|exists:product_variants,id',
                'quantity' => 'required|integer|min:1'
            ]);

            if (Auth::check()) {
                $cart = Cart::updateOrCreate(
                    [
                        'user_id' => Auth::id(),
                        'variant_id' => $validated['variant_id']
                    ],
                    [
                        'quantity' => DB::raw('quantity + ' . $validated['quantity'])
                    ]
                );
                return response()->json(['cart_item' => $cart->load('variant.product')]);
            }

            // Guest cart management
            $guestCart = $request->session()->get('guest_cart', []);
            $existingItemKey = collect($guestCart)->search(fn($item) => $item['variant_id'] == $validated['variant_id']);

            if ($existingItemKey !== false) {
                $guestCart[$existingItemKey]['quantity'] += $validated['quantity'];
            } else {
                $guestCart[] = [
                    'variant_id' => $validated['variant_id'],
                    'quantity' => $validated['quantity']
                ];
            }

            $request->session()->put('guest_cart', $guestCart);
            return response()->json(['cart_item' => end($guestCart)], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add item to cart: ' . $e->getMessage()], 500);
        }
    }

    public function createAccountAndMigrateCart(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        try {
            // Create new user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

            // Migrate guest cart to user's cart
            $guestCart = $request->session()->get('guest_cart', []);
            foreach ($guestCart as $item) {
                Cart::create([
                    'user_id' => $user->id,
                    'variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity']
                ]);
            }

            // Clear guest cart
            $request->session()->forget('guest_cart');

            // Authenticate the new user
            auth()->login($user);

            return response()->json([
                'user' => $user,
                'message' => 'Account created and cart migrated successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create account and migrate cart: ' . $e->getMessage()], 500);
        }
    }

    public function updateQuantity(Request $request, $cartItemId) {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            if (Auth::check()) {
                $cart = Cart::findOrFail($cartItemId);
                $cart->update($validated);
                return response()->json(['cart_item' => $cart->load('variant.product')]);
            }

            // Guest cart quantity update
            $guestCart = $request->session()->get('guest_cart', []);
            $key = collect($guestCart)->search(fn($item) => $item['variant_id'] == $cartItemId);

            if ($key !== false) {
                $guestCart[$key]['quantity'] = $validated['quantity'];
                $request->session()->put('guest_cart', $guestCart);
                return response()->json(['cart_item' => $guestCart[$key]]);
            }

            return response()->json(['error' => 'Cart item not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update quantity: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($cartItemId) {
        try {
            if (Auth::check()) {
                $cart = Cart::findOrFail($cartItemId);
                $cart->delete();
                return response()->json([], 204);
            }

            // Guest cart item removal
            $guestCart = session()->get('guest_cart', []);
            $key = collect($guestCart)->search(fn($item) => $item['variant_id'] == $cartItemId);

            if ($key !== false) {
                unset($guestCart[$key]);
                session()->put('guest_cart', array_values($guestCart));
                return response()->json([], 204);
            }

            return response()->json(['error' => 'Cart item not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete cart item: ' . $e->getMessage()], 500);
        }
    }
}
