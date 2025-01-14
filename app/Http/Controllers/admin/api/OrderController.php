<?php

namespace App\Http\Controllers\admin\api;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.variant.product', 'shippingAddress'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json(['orders' => $orders]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipping_address_id' => 'required|exists:addresses,id',
            'billing_address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            // Get cart items
            $cartItems = Cart::with('variant.product')
                ->where('user_id', auth()->id())
                ->get();

            if ($cartItems->isEmpty()) {
                return response()->json(['message' => 'Cart is empty'], 400);
            }

            // Calculate totals
            $subtotal = $cartItems->sum(function($item) {
                return $item->variant->getFinalPriceAttribute() * $item->quantity;
            });

            $tax = $subtotal * 0.1; // 10% tax
            $shipping = 10.00; // Fixed shipping cost
            $total = $subtotal + $tax + $shipping;

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'shipping_address_id' => $validated['shipping_address_id'],
                'billing_address_id' => $validated['billing_address_id'],
                'total_amount' => $total,
                'tax_amount' => $tax,
                'shipping_cost' => $shipping,
                'order_status' => 'pending'
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                $order->items()->create([
                    'variant_id' => $item->variant_id,
                    'quantity' => $item->quantity,
                    'price_per_unit' => $item->variant->getFinalPriceAttribute()
                ]);

                // Reduce stock
                $item->variant->decrement('stock_quantity', $item->quantity);
            }

            // Clear cart
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order->load('items.variant.product')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Order creation failed'], 500);
        }
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'order' => $order->load(['items.variant.product', 'shippingAddress', 'billingAddress'])
        ]);
    }
}
