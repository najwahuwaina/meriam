<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Jurnal;
use Carbon\Carbon;

class BukuBesar extends Widget
{
    protected static string $view = 'filament.widgets.buku-besar';

    protected int|string|array $columnSpan = 'full';

    public ?string $periode_awal = null;
    public ?string $periode_akhir = null;
    public ?string $akun = null;

    protected $listeners = [
        'filterUpdated' => '$refresh',
    ];

    public function mount(): void
    {
        $this->periode_awal = request('periode_awal') ?? now()->format('Y-m');
        $this->periode_akhir = request('periode_akhir') ?? now()->format('Y-m');
        $this->akun = request('akun');
    }

    public function getViewData(): array
    {
        $saldoAwal = 0;

        $jurnalsQuery = Jurnal::with(['jurnaldetail' => function ($query) {
            if ($this->akun) {
                $query->where('akun', $this->akun);
            }
            $query->with('akun');
        }])
        ->orderBy('tanggal', 'asc')
        ->orderBy('id', 'asc');

        if ($this->periode_awal && $this->periode_akhir) {
            $awal = Carbon::createFromFormat('Y-m', $this->periode_awal)->startOfMonth();
            $akhir = Carbon::createFromFormat('Y-m', $this->periode_akhir)->endOfMonth();

            $saldoAwal = Jurnal::where('tanggal', '<', $awal)
                ->with(['jurnaldetail' => function ($query) {
                    if ($this->akun) {
                        $query->where('akun', $this->akun);
                    }
                }])
                ->get()
                ->flatMap->jurnaldetail
                ->reduce(fn ($carry, $detail) => $carry + ((float) $detail->debit - (float) $detail->kredit), 0);

            $jurnalsQuery->whereBetween('tanggal', [$awal, $akhir]);
        }

        return [
            'jurnals'       => $jurnalsQuery->get(),
            'periode_awal'  => $this->periode_awal,
            'periode_akhir' => $this->periode_akhir,
            'saldoAwal'     => $saldoAwal,
            'akun'          => $this->akun,
        ];
    }
}
