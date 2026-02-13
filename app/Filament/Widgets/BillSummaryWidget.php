<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class BillSummaryWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        if (!$user || !$user->customer || !$user->customer->billing) {
            return [
                Stat::make('Monthly Bill', '0.00'),
                Stat::make('Paid Amount', '0.00'),
                Stat::make('Total Due', '0.00'),
            ];
        }

        $billing = $user->customer->billing;

        return [
            Stat::make('Monthly Bill', number_format($billing->monthly_rent, 2))
                ->description('Current Plan Rent')
                ->color('info'),
            Stat::make('Paid Amount', number_format($billing->paid_amount, 2))
                ->description('Amount paid this month')
                ->color('success'),
            Stat::make('Total Due', number_format($billing->total_due_amount, 2))
                ->description('Pending balance')
                ->color($billing->total_due_amount > 0 ? 'danger' : 'success'),
        ];
    }
}
