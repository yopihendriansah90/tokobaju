<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('status', 'all');

        $ordersQuery = Order::query()
            ->where('user_id', auth()->id())
            ->with(['items.product'])
            ->latest();

        if ($filter === 'unconfirmed') {
            $ordersQuery->whereIn('payment_status', ['awaiting_payment', 'awaiting_confirmation']);
        }

        $orders = $ordersQuery->paginate(10)->withQueryString();

        return view('client.orders.index', [
            'orders' => $orders,
            'filter' => $filter,
        ]);
    }

    public function payment(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        return view('client.orders.payment', [
            'order' => $order,
        ]);
    }

    public function storePayment(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        $validated = $request->validate([
            'payment_notes' => 'nullable|string|max:500',
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:4096',
        ], [
            'payment_proof.required' => 'Bukti transfer wajib diunggah.',
            'payment_proof.mimes' => 'Format bukti transfer harus jpg, jpeg, png, atau pdf.',
            'payment_proof.max' => 'Ukuran bukti transfer maksimal 4MB.',
        ]);

        $order->update([
            'payment_status' => 'awaiting_confirmation',
            'payment_notes' => $validated['payment_notes'] ?? null,
        ]);

        $order
            ->addMediaFromRequest('payment_proof')
            ->toMediaCollection('payment_proof');

        return redirect()
            ->route('checkout.success', $order)
            ->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu konfirmasi admin.');
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        $order->load(['items.product']);

        return view('client.orders.show', [
            'order' => $order,
        ]);
    }
}
