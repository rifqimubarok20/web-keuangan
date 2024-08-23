<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;
    protected function getStats(): array
    {
        $startDate = !is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = !is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        $income = Transaction::income()->whereBetween('transaction_date', [$startDate, $endDate])->sum('amount');
        $expenditure = Transaction::expense()->whereBetween('transaction_date', [$startDate, $endDate])->sum('amount');

        return [
            Stat::make('Total Income', 'IDR ' . $income),
            Stat::make('Total Expenditure', 'IDR ' . $expenditure),
            Stat::make('difference', 'IDR ' . $income - $expenditure),
        ];
    }
}
