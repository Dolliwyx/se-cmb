<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class DailyTransactionCount extends ChartWidget
{
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Continuous Receipt Transactions',
                    'data' => DB::table('financial_transactions')
                        ->where('transaction_type', 0)
                        ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as amount'))
                        ->groupBy('date')
                        ->pluck('amount', 'date')
                        ->toArray(),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                ],
                [
                    'label' => 'Manual Receipt Transactions',
                    'data' => DB::table('financial_transactions')
                        ->where('transaction_type', 1)
                        ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as amount'))
                        ->groupBy('date')
                        ->pluck('amount', 'date')
                        ->toArray(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ],
            ],
            'labels' => DB::table('financial_transactions')
                ->select(DB::raw('DATE(created_at) as date'))
                ->whereBetween('created_at', [now()->addDays(5), now()])
                ->groupBy('date')
                ->orderBy('created_at', 'desc')
                ->pluck('date')
                ->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
