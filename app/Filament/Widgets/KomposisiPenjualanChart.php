<?php

namespace App\Filament\Widgets;

use App\Models\DetailPesanan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class KomposisiPenjualanChart extends ChartWidget
{
    protected static ?string $heading = 'Komposisi Penjualan Menu';

    protected int|string|array $columnSpan = 1;

    protected static ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $data = DetailPesanan::join('menus', 'detail_pesanan.id_menu', '=', 'menus.id')
            ->select(
                'menus.jenis_menu',
                DB::raw('SUM(detail_pesanan.jumlah) as total')
            )
            ->groupBy('menus.jenis_menu')
            ->get();

        return [
            'datasets' => [
                [
                    'data' => $data->pluck('total')->toArray(),

                    'backgroundColor' => [
                        '#F59E0B',
                        '#10B981',
                        '#3B82F6',
                        '#EF4444',
                        '#8B5CF6',
                        '#EC4899',
                    ],

                    'borderWidth' => 2,
                    'hoverOffset' => 12,
                ],
            ],

            'labels' => $data->pluck('jenis_menu')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,

            'plugins' => [
                'legend' => [
                    'position' => 'right',
                    'labels' => [
                        'padding' => 20,
                        'boxWidth' => 14,
                    ],
                ],
            ],
        ];
    }
}