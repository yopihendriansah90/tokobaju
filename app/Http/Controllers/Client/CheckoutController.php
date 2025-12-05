<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create()
    {
        $cart = collect(session('cart', []));
        $productIds = $cart->pluck('product_id')->all();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $cartItems = $cart->map(function ($item) use ($products) {
            $product = $products->get($item['product_id']);

            if (!$product) {
                return null;
            }

            return [
                'product' => $product,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $product->price * $item['quantity'],
            ];
        })->filter();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.products.index')->with('error', 'Keranjang masih kosong.');
        }

        $subtotal = $cartItems->sum('subtotal');

        return view('client.checkout.create', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
        ]);
    }

    public function store(Request $request)
    {
        $cart = collect(session('cart', []));
        $productIds = $cart->pluck('product_id')->all();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $cartItems = $cart->map(function ($item) use ($products) {
            $product = $products->get($item['product_id']);

            if (!$product) {
                return null;
            }

            return [
                'product' => $product,
                'quantity' => $item['quantity'],
                'price' => $product->price,
                'subtotal' => $product->price * $item['quantity'],
            ];
        })->filter();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.products.index')->with('error', 'Keranjang masih kosong.');
        }

        $insufficientStock = $cartItems->first(fn ($item) => $item['quantity'] > $item['product']->stock);
        if ($insufficientStock) {
            return redirect()->route('cart.index')->with('error', 'Stok produk ' . $insufficientStock['product']->name . ' tidak mencukupi.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:50',
            'shipping_address' => 'required|string|max:500',
            'payment_notes' => 'nullable|string|max:500',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ]);

        $subtotal = $cartItems->sum('subtotal');
        $paymentStatus = $request->hasFile('payment_proof') ? 'awaiting_confirmation' : 'awaiting_payment';

        $order = Order::create([
            'user_id' => auth()->id(),
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_phone' => $validated['customer_phone'],
            'shipping_address' => $validated['shipping_address'],
            'status' => 'pending',
            'total_amount' => $subtotal,
            'payment_method' => 'bank_transfer',
            'payment_status' => $paymentStatus,
            'payment_notes' => $validated['payment_notes'] ?? null,
            'payment_reference' => 'INV-' . Str::upper(Str::random(8)),
        ]);

        foreach ($cartItems as $item) {
            /** @var Product $product */
            $product = $item['product'];

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price,
            ]);

            $product->decrement('stock', $item['quantity']);
        }

        if ($request->hasFile('payment_proof')) {
            $order
                ->addMediaFromRequest('payment_proof')
                ->toMediaCollection('payment_proof');
        }

        session()->forget('cart');

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order)
    {
        return view('client.checkout.success', compact('order'));
    }
}
