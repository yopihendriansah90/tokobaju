<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class CartService
{
    const CART_SESSION_KEY = 'shopping_cart';

    /**
     * Get the current cart contents.
     *
     * @return \Illuminate\Support\Collection<array{product: Product, quantity: int, price: float}>
     */
    public function getCart(): Collection
    {
        return session()->get(self::CART_SESSION_KEY, collect());
    }

    /**
     * Add a product to the cart.
     *
     * @param Product $product
     * @param int $quantity
     * @return void
     */
    public function add(Product $product, int $quantity = 1): void
    {
        $cart = $this->getCart();

        if ($cart->has($product->id)) {
            $item = $cart->get($product->id);
            $item['quantity'] += $quantity;
            $cart->put($product->id, $item);
        } else {
            $cart->put($product->id, [
                'product' => $product,
                'quantity' => $quantity,
                'price' => $product->price, // Store current price at the time of adding
            ]);
        }

        session()->put(self::CART_SESSION_KEY, $cart);
    }

    /**
     * Update the quantity of a product in the cart.
     *
     * @param Product $product
     * @param int $quantity
     * @return void
     */
    public function update(Product $product, int $quantity): void
    {
        $cart = $this->getCart();

        if ($cart->has($product->id)) {
            $item = $cart->get($product->id);
            $item['quantity'] = $quantity;

            if ($item['quantity'] <= 0) {
                $cart->forget($product->id);
            } else {
                $cart->put($product->id, $item);
            }
        }

        session()->put(self::CART_SESSION_KEY, $cart);
    }

    /**
     * Remove a product from the cart.
     *
     * @param Product $product
     * @return void
     */
    public function remove(Product $product): void
    {
        $cart = $this->getCart();
        $cart->forget($product->id);
        session()->put(self::CART_SESSION_KEY, $cart);
    }

    /**
     * Get the total number of items in the cart (sum of quantities).
     *
     * @return int
     */
    public function getTotalQuantity(): int
    {
        return $this->getCart()->sum('quantity');
    }

    /**
     * Get the total amount of the cart.
     *
     * @return float
     */
    public function getTotalAmount(): float
    {
        return $this->getCart()->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });
    }

    /**
     * Clear the cart.
     *
     * @return void
     */
    public function clear(): void
    {
        session()->forget(self::CART_SESSION_KEY);
    }
}
