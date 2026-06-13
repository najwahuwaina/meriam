<?php

namespace App\Filament\Widgets;

use App\Models\DetailPesanan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MenuTerlarisChart extends ChartWidget
{
    protected static ?string $heading = 'Menu Terlaris';

    protected int|string|array $columnSpan = 1;

    protected static ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $data = DetailPesanan::join('menus', 'detail_pesanan.id_menu', '=', 'menus.id')
            ->select(
                'menus.nama_menu',
                DB::raw('SUM(detail_pesanan.jumlah) as total')
            )
            ->groupBy('menus.nama_menu')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Terjual',

                    'data' => $data->pluck('total')->toArray(),

                    'backgroundColor' => [
                        '#F59E0B',
                        '#FBBF24',
                        '#FCD34D',
                        '#FDE68A',
                        '#FEF3C7',
                    ],

                    'borderColor' => '#D97706',
                    'borderWidth' => 2,
                    'borderRadius' => 10,
                    'borderSkipped' => false,
                ],
            ],

            'labels' => $data->pluck('nama_menu')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'maintainAspectRatio' => false,

            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],

            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}