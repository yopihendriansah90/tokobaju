<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create()
    {
        $cartItems = $this->buildCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.products.index')->with('error', 'Keranjang masih kosong.');
        }

        $subtotal = $cartItems->sum('subtotal');
        $shippingOptions = $this->getShippingOptions();

        return view('client.checkout.create', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'shippingOptions' => $shippingOptions,
        ]);
    }

    public function store(Request $request)
    {
        $cartItems = $this->buildCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('client.products.index')->with('error', 'Keranjang masih kosong.');
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:50|regex:/^[0-9+\\s()-]+$/',
            'shipping_address' => 'required|string|max:500',
            'shipping_method' => ['required', 'string', Rule::in(array_keys($this->getShippingOptions()))],
        ], [
            'customer_phone.regex' => 'Nomor telepon hanya boleh berisi angka, spasi, tanda +, (), atau -.',
            'shipping_method.required' => 'Metode pengiriman wajib dipilih.',
            'shipping_method.in' => 'Metode pengiriman tidak valid.',
        ]);

        $shippingOptions = $this->getShippingOptions();
        $shippingCost = $shippingOptions[$validated['shipping_method']]['cost'] ?? 0;
        $insufficientStockProduct = null;
        $order = null;

        try {
            DB::transaction(function () use ($cartItems, $validated, $shippingCost, &$insufficientStockProduct, &$order) {
                $productIds = $cartItems->pluck('product.id')->all();
                $products = Product::whereIn('id', $productIds)->lockForUpdate()->get()->keyBy('id');

                $subtotal = 0;

                foreach ($cartItems as $item) {
                    /** @var Product|null $product */
                    $product = $products->get($item['product']->id);

                    if (! $product) {
                        $insufficientStockProduct = $item['product'];
                        throw new \RuntimeException('Produk sudah tidak tersedia.');
                    }

                    if ($item['quantity'] > $product->stock) {
                        $insufficientStockProduct = $product;
                        throw new \RuntimeException('Stok tidak mencukupi.');
                    }

                    $subtotal += $product->price * $item['quantity'];
                }

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'status' => 'pending',
                    'total_amount' => $subtotal + $shippingCost,
                    'shipping_method' => $validated['shipping_method'],
                    'shipping_cost' => $shippingCost,
                    'payment_method' => 'bank_transfer',
                    'payment_status' => 'awaiting_payment',
                    'payment_notes' => null,
                    'payment_reference' => 'INV-' . Str::upper(Str::random(8)),
                ]);

                foreach ($cartItems as $item) {
                    /** @var Product $product */
                    $product = $products->get($item['product']->id);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                    ]);

                    $product->decrement('stock', $item['quantity']);
                }
            });
        } catch (\RuntimeException $exception) {
            if ($insufficientStockProduct) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', 'Stok produk ' . $insufficientStockProduct->name . ' tidak mencukupi.');
            }

            throw $exception;
        }

        session()->forget('cart');

        return redirect()->route('orders.payment', $order);
    }

    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        return view('client.checkout.success', compact('order'));
    }

    private function buildCartItems(): Collection
    {
        $cart = collect(session('cart', []));

        if ($cart->isEmpty()) {
            return collect();
        }

        $productIds = $cart->pluck('product_id')->filter()->unique()->all();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $cartItems = $cart->map(function ($item) use ($products) {
            $product = $products->get($item['product_id']);

            if (! $product) {
                return null;
            }

            $quantity = max(1, (int) ($item['quantity'] ?? 1));

            return [
                'product' => $product,
                'quantity' => $quantity,
                'price' => $product->price,
                'subtotal' => $product->price * $quantity,
            ];
        })->filter();

        if ($cartItems->isEmpty()) {
            session()->forget('cart');
            return collect();
        }

        $normalizedCart = $cartItems->mapWithKeys(function ($item) {
            return [
                $item['product']->id => [
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                ],
            ];
        })->all();

        session()->put('cart', $normalizedCart);

        return $cartItems;
    }

    private function getShippingOptions(): array
    {
        return [
            'regular' => [
                'label' => 'Reguler (2-3 hari)',
                'cost' => 20000,
            ],
            'express' => [
                'label' => 'Express (1-2 hari)',
                'cost' => 35000,
            ],
            'instant' => [
                'label' => 'Instant (dalam hari)',
                'cost' => 50000,
            ],
        ];
    }
}
