<?php

namespace App\Filament\Resources\PenggajianResource\Pages;

use App\Filament\Resources\PenggajianResource;
use App\Models\Presensi;
use Filament\Resources\Pages\CreateRecord;

class CreatePenggajian extends CreateRecord
{
    protected static string $resource = PenggajianResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $jumlahHadir = Presensi::where('id_karyawan', $data['id_karyawan'])
            ->whereMonth('tanggal', (int) $data['bulan'])
            ->whereYear('tanggal', (int) $data['tahun'])
            ->whereRaw('LOWER(status) = ?', ['hadir'])
            ->count();

        $jumlahIzin = Presensi::where('id_karyawan', $data['id_karyawan'])
            ->whereMonth('tanggal', (int) $data['bulan'])
            ->whereYear('tanggal', (int) $data['tahun'])
            ->whereRaw('LOWER(status) = ?', ['izin'])
            ->count();

        $jumlahSakit = Presensi::where('id_karyawan', $data['id_karyawan'])
            ->whereMonth('tanggal', (int) $data['bulan'])
            ->whereYear('tanggal', (int) $data['tahun'])
            ->whereRaw('LOWER(status) = ?', ['sakit'])
            ->count();

        $jumlahAlpa = Presensi::where('id_karyawan', $data['id_karyawan'])
            ->whereMonth('tanggal', (int) $data['bulan'])
            ->whereYear('tanggal', (int) $data['tahun'])
            ->whereRaw('LOWER(status) = ?', ['alpa'])
            ->count();

        $data['jumlah_hadir'] = $jumlahHadir;
        $data['jumlah_izin']  = $jumlahIzin;
        $data['jumlah_sakit'] = $jumlahSakit;
        $data['jumlah_alpa']  = $jumlahAlpa;

        // Simpan nilai per hari
        $tunjangan_transport = (float) ($data['tunjangan_transport'] ?? 0);
        $tunjangan_makan     = (float) ($data['tunjangan_makan'] ?? 0);

        $data['tunjangan_transport'] = $tunjangan_transport;
        $data['tunjangan_makan']     = $tunjangan_makan;

        // Total tunjangan = (transport + makan) per hari x jumlah hadir
        $data['total_tunjangan'] = ($tunjangan_transport + $tunjangan_makan) * $jumlahHadir;

        // Total gaji = gaji pokok + total tunjangan
        $gaji_pokok         = $jumlahHadir * (float) $data['gaji_per_hari'];
        $data['total_gaji'] = $gaji_pokok + $data['total_tunjangan'];

        return $data;
    }
}