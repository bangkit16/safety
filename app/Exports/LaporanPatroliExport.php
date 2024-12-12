<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Perbaikan; // Model Anda untuk mengambil data dari database

class LaporanPatroliExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Ambil data dari database
        return Perbaikan::all(['temuan', 'keterangan', 'perbaikan', 'target', 'status']);
    }

    public function headings(): array
    {
        return [
            'No',
            'Temuan',
            'Keterangan',
            'Rekomendasi Perbaikan',
            'Target',
            'Status',
        ];
    }
}
