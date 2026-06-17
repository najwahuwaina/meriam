<?php

namespace App\Filament\Widgets;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\BahanBaku;
use App\Models\Presensi;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make(
                'Total Penjualan Hari Ini',
                'Rp ' . number_format(
                    Pesanan::whereDate('tgl_pesanan', today())
                        ->sum('total_harga'),
                    0,
                    ',',
                    '.'
                )
            )
                ->description('Omzet hari ini')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([5, 8, 10, 15, 12, 18, 20]),

            Stat::make(
                'Total Pesanan Hari Ini',
                Pesanan::whereDate('tgl_pesanan', today())->count()
            )
                ->description('Pesanan masuk hari ini')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('primary')
                ->chart([2, 5, 8, 6, 10, 12, 15]),

            Stat::make(
                'Menu Terjual Hari Ini',
                DetailPesanan::whereDate('created_at', today())
                    ->sum('jumlah') . ' Porsi'
            )
                ->description('Total menu terjual')
                ->descriptionIcon('heroicon-m-fire')
                ->color('warning')
                ->chart([10, 20, 15, 30, 25, 35, 40]),

            Stat::make(
                'Total Pendapatan',
                'Rp ' . number_format(
                    Pesanan::sum('total_harga'),
                    0,
                    ',',
                    '.'
                )
            )
                ->description('Pendapatan keseluruhan')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success')
                ->chart([50, 60, 55, 70, 80, 90, 100]),

            Stat::make(
                'Karyawan Hadir Hari Ini',
                Presensi::whereDate('tanggal', today())
                    ->where('status', 'Hadir')
                    ->count() . ' Orang'
            )
                ->description('Kehadiran hari ini')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info')
                ->chart([4, 5, 6, 5, 7, 8, 8]),

            Stat::make(
                'Stok Menipis',
                BahanBaku::whereColumn('stok', '<=', 'stok_minimum')
                    ->count() . ' Item'
            )
                ->description('Segera lakukan pembelian')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger')
                ->chart([8, 7, 6, 5, 5, 4, 3]),

        ];
    }
}