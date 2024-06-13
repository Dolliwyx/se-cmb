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
        // get today's complete month
        $month = date('F');
        $year = date('Y');

        // get complete month, day, and year
        $date = date('F j, Y');

        return [
            Stat::make("Total collection for continous receipt {$date}", "PHP " . DB::table('financial_transactions')->where('transaction_type', 0)->whereDate('created_at', date('y-m-d'))->sum('amount')),
            Stat::make("Total collection for manual receipt {$date}", "PHP " . DB::table('financial_transactions')->where('transaction_type', 1)->whereDate('created_at', date('y-m-d'))->sum('amount')),
            Stat::make("Total collection for {$date}", "PHP " . DB::table('financial_transactions')->whereDate('created_at', date('y-m-d'))->sum('amount')),
            Stat::make("Total collection for continuous receipt {$month} {$year}", "PHP " . DB::table('financial_transactions')->where('transaction_type', 0)->whereMonth('created_at', date('m'))->sum('amount')),
            Stat::make("Total collection for manual receipt {$month} {$year}", "PHP " . DB::table('financial_transactions')->where('transaction_type', 1)->whereMonth('created_at', date('m'))->sum('amount')),
            Stat::make("Total collection for {$month} {$year}", "PHP " . DB::table('financial_transactions')->whereMonth('created_at', date('m'))->sum('amount')),
        ];
    }
}
