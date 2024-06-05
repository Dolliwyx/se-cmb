<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class MonthlyTotalTransaction extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        return [
            Stat::make('Total collection for continuous receipt', "PHP " . DB::table('financial_transactions')->where('transaction_type', 0)->sum('amount')),
            Stat::make('Total collection for manual receipt', "PHP " . DB::table('financial_transactions')->where('transaction_type', 1)->sum('amount')),
            Stat::make('Total collection for this month', "PHP " . DB::table('financial_transactions')->whereMonth('created_at', date('m'))->sum('amount')),
        ];
    }
}
