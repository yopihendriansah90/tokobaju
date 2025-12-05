<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SalesTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Penjualan 7 Hari Terakhir';

    protected function getData(): array
    {
        $dates = collect(range(0, 6))->map(fn ($day) => Carbon::today()->subDays($day))->reverse();

        $sales = $dates->map(function (Carbon $date) {
            return Order::whereDate('created_at', $date)
                ->whereIn('payment_status', ['awaiting_confirmation', 'paid'])
                ->sum('total_amount');
        });

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan Harian',
                    'data' => $sales->values(),
                    'borderColor' => '#4f8a63',
                    'backgroundColor' => 'rgba(79,138,99,0.15)',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $dates->map(fn ($date) => $date->format('d M'))->all(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
