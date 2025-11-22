<?php

namespace App\Exports;

use App\Models\AbsenGuru;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class AbsenGuruExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return AbsenGuru::with(['guru', 'mataPelajaran'])
            ->get()
            ->map(function ($item) {
                return [
                    'Tanggal' => Carbon::parse($item->tanggal)->format('d-m-Y'),
                    'Guru' => $item->guru->nama_guru ?? '-',
                    'Mata Pelajaran' => $item->mataPelajaran->nama_mata_pelajaran ?? '-',
                    'Status Kehadiran' => $item->status ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Guru',
            'Mata Pelajaran',
            'Status Kehadiran',
        ];
    }
}
