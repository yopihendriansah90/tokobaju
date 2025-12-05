<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class SalesOverview extends StatsOverviewWidget
{
    protected function getCards(): array
    {
        $paidTotal = Order::where('payment_status', 'paid')->sum('total_amount');
        $pendingPayment = Order::where('payment_status', 'awaiting_payment')->count();
        $completed = Order::where('status', 'completed')->count();

        return [
            Card::make('Total Pendapatan', 'Rp ' . number_format($paidTotal, 0, ',', '.'))
                ->color('success')
                ->description('Order terbayar')
                ->descriptionIcon('heroicon-o-check-circle'),
            Card::make('Menunggu Pembayaran', $pendingPayment . ' order')
                ->color('warning')
                ->description('Perlu follow up')
                ->descriptionIcon('heroicon-o-clock'),
            Card::make('Order Selesai', $completed . ' order')
                ->color('primary')
                ->description('Telah dikirim/selesai')
                ->descriptionIcon('heroicon-o-cube'),
        ];
    }
}
