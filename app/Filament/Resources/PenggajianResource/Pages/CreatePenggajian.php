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
        // Hitung jumlah hadir
        $jumlahHadir = Presensi::where('id_karyawan', $data['id_karyawan'])
            ->whereMonth('tanggal', (int) $data['bulan'])
            ->whereYear('tanggal', (int) $data['tahun'])
            ->whereRaw('LOWER(status) = ?', ['hadir'])
            ->count();

        // Hitung izin
        $jumlahIzin = Presensi::where('id_karyawan', $data['id_karyawan'])
            ->whereMonth('tanggal', (int) $data['bulan'])
            ->whereYear('tanggal', (int) $data['tahun'])
            ->whereRaw('LOWER(status) = ?', ['izin'])
            ->count();

        // Hitung sakit
        $jumlahSakit = Presensi::where('id_karyawan', $data['id_karyawan'])
            ->whereMonth('tanggal', (int) $data['bulan'])
            ->whereYear('tanggal', (int) $data['tahun'])
            ->whereRaw('LOWER(status) = ?', ['sakit'])
            ->count();

        // Hitung alpa
        $jumlahAlpa = Presensi::where('id_karyawan', $data['id_karyawan'])
            ->whereMonth('tanggal', (int) $data['bulan'])
            ->whereYear('tanggal', (int) $data['tahun'])
            ->whereRaw('LOWER(status) = ?', ['alpa'])
            ->count();

        // Simpan hasil
        $data['jumlah_hadir'] = $jumlahHadir;
        $data['jumlah_izin'] = $jumlahIzin;
        $data['jumlah_sakit'] = $jumlahSakit;
        $data['jumlah_alpa'] = $jumlahAlpa;

        // Hitung total gaji
        $data['total_gaji'] = $jumlahHadir * $data['gaji_per_hari'];

        return $data;
    }
}