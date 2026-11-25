<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Ump;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UmpExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Enumerable
    {
        return Ump::with('provinsi')
            ->orderBy('tahun', 'desc')
            ->orderBy('provinsi_id')
            ->get();
    }

    public function headings(): array
    {
        return [
            'kode_provinsi',
            'nama_provinsi',
            'tahun',
            'nominal',
        ];
    }

    public function map(mixed $ump): array
    {
        return [
            $ump->provinsi->kode_provinsi ?? '',
            $ump->provinsi->nama_provinsi ?? '',
            $ump->tahun,
            $ump->nominal,
        ];
    }
}
