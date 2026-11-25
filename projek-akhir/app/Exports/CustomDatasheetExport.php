<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Ump;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomDatasheetExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected ?int $provinsiId;
    protected ?int $tahunDari;
    protected ?int $tahunSampai;

    public function __construct(?int $provinsiId = null, ?int $tahunDari = null, ?int $tahunSampai = null)
    {
        $this->provinsiId = $provinsiId;
        $this->tahunDari = $tahunDari;
        $this->tahunSampai = $tahunSampai;
    }

    public function collection(): Enumerable
    {
        $query = Ump::with('provinsi');

        if ($this->provinsiId) {
            $query->where('provinsi_id', $this->provinsiId);
        }

        if ($this->tahunDari) {
            $query->where('tahun', '>=', $this->tahunDari);
        }

        if ($this->tahunSampai) {
            $query->where('tahun', '<=', $this->tahunSampai);
        }

        return $query->orderBy('tahun', 'desc')
            ->orderBy('provinsi_id', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID Data',
            'Kode Provinsi',
            'Nama Provinsi',
            'Tahun Periode',
            'Nominal UMP (IDR)',
            'Status Data',
            'Tanggal Dikeluarkan',
        ];
    }

    public function map(mixed $ump): array
    {
        return [
            'NW-' . $ump->id,
            $ump->provinsi->kode_provinsi ?? '-',
            $ump->provinsi->nama_provinsi ?? '-',
            (int) $ump->tahun,
            (float) $ump->nominal,
            'Resmi Terverifikasi',
            $ump->created_at ? $ump->created_at->format('Y-m-d') : date('Y-m-d'),
        ];
    }

    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF059669'], // Emerald green
                ],
            ],
        ];
    }
}
