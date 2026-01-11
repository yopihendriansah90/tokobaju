<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class OrderMonitoring extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        $totalOrders = Order::count();
        $awaitingPayment = Order::where('payment_status', 'awaiting_payment')->count();
        $awaitingConfirmation = Order::where('payment_status', 'awaiting_confirmation')->count();
        $paidOrders = Order::where('payment_status', 'paid')->count();

        return [
            Card::make('Total Transaksi', $totalOrders . ' order')
                ->color('primary')
                ->description('Semua order masuk')
                ->descriptionIcon('heroicon-o-receipt-percent'),
            Card::make('Menunggu Pembayaran', $awaitingPayment . ' order')
                ->color('warning')
                ->description('Perlu follow up')
                ->descriptionIcon('heroicon-o-clock'),
            Card::make('Menunggu Konfirmasi', $awaitingConfirmation . ' order')
                ->color('warning')
                ->description('Cek bukti transfer')
                ->descriptionIcon('heroicon-o-document-check'),
            Card::make('Lunas', $paidOrders . ' order')
                ->color('success')
                ->description('Pembayaran selesai')
                ->descriptionIcon('heroicon-o-check-circle'),
        ];
    }
}
