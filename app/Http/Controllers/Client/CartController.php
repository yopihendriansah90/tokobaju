<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = collect(session('cart', []));

        $productIds = $cart->pluck('product_id')->all();
        $products = Product::with('category')->whereIn('id', $productIds)->get()->keyBy('id');

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

        $subtotal = $cartItems->sum('subtotal');

        return view('client.cart.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
        ]);
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = $validated['quantity'] ?? 1;
        $cart = session()->get('cart', []);

        $newQuantity = ($cart[$product->id]['quantity'] ?? 0) + $quantity;

        if ($product->stock < $newQuantity) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi.');
        }

        $cart[$product->id] = [
            'product_id' => $product->id,
            'quantity' => $newQuantity,
        ];

        session()->put('cart', $cart);

        if ($request->input('redirect') === 'checkout') {
            return redirect()
                ->route('checkout.create')
                ->with('success', 'Produk ditambahkan, lanjutkan checkout.');
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (!isset($cart[$product->id])) {
            return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan di keranjang.');
        }

        if ($product->stock < $validated['quantity']) {
            return redirect()->route('cart.index')->with('error', 'Stok produk tidak mencukupi.');
        }

        $cart[$product->id]['quantity'] = $validated['quantity'];
        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Keranjang diperbarui.');
    }

    public function destroy(Product $product)
    {
        $cart = session()->get('cart', []);

        unset($cart[$product->id]);

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }
}
