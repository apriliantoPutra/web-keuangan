<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
        Carbon::parse($this->filters['startDate']) :
        null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
        Carbon::parse($this->filters['endDate']) :
        now();
        // Menghitung total pemasukan dan pengeluaran
        $pemasukan = Transaction::incomes()
        ->whereBetween('date_transaction', [$startDate, $endDate])
        ->sum('amount');
        $pengeluaran = Transaction::expenses()->sum('amount');

        // Mengembalikan array statik untuk ditampilkan pada dashboard
        return [
            Stat::make('Total Pemasukan', 'IDR ' .$pemasukan),
            Stat::make('Total Pengeluaran', 'IDR ' .$pengeluaran),
            Stat::make('Selisih', 'IDR '. $pemasukan - $pengeluaran),
        ];
    }
}
