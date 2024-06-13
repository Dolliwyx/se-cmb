<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailyTransactionCount extends ChartWidget
{
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $startDate = now()->subDays(20)->startOfDay(); // 5 days ago including today
        $endDate = now()->endOfDay(); // End of today

        $continuousReceiptTransactions = DB::table('financial_transactions')
            ->where('transaction_type', 0)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as amount'))
            ->groupBy('date')
            ->pluck('amount', 'date')
            ->toArray();

        $manualReceiptTransactions = DB::table('financial_transactions')
            ->where('transaction_type', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as amount'))
            ->groupBy('date')
            ->pluck('amount', 'date')
            ->toArray();

        $dates = DB::table('financial_transactions')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('date')
            ->toArray();

        $labels = array_map(function ($date) {
            return Carbon::parse($date)->format('m/d/y'); // Format as "06/13/24"
        }, $dates);

        return [
            'datasets' => [
                [
                    'label' => 'Continuous Receipt Transactions',
                    'data' => array_values($continuousReceiptTransactions),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                ],
                [
                    'label' => 'Manual Receipt Transactions',
                    'data' => array_values($manualReceiptTransactions),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
