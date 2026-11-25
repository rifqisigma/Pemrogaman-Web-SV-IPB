<?php

namespace App\Imports;

use App\Models\Provinsi;
use App\Models\Ump;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UmpImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $errors = [];

        foreach ($rows as $index => $row) {
            // Baris data di Excel dimulai setelah header (baris ke-1 = header, baris ke-2 = data pertama)
            $excelRow = $index + 2;

            $kodeProvinsi = trim((string) ($row['kode_provinsi'] ?? ''));
            $namaProvinsi = trim((string) ($row['nama_provinsi'] ?? ''));
            $tahun = $row['tahun'] ?? null;
            $nominal = $row['nominal'] ?? null;

            // Lewati baris yang benar-benar kosong semua
            if (
                $kodeProvinsi === '' &&
                $namaProvinsi === '' &&
                ($tahun === null || $tahun === '') &&
                ($nominal === null || $nominal === '')
            ) {
                continue;
            }

            // Bersihkan tanda pemisah ribuan jika ada (misal 2.500.000 atau 2,500,000)
            if (is_string($nominal)) {
                $cleanedNominal = preg_replace('/[^\d.]/', '', str_replace(',', '', $nominal));
                if (substr_count($cleanedNominal, '.') > 1) {
                    $cleanedNominal = str_replace('.', '', $cleanedNominal);
                }
                $nominal = $cleanedNominal !== '' ? $cleanedNominal : $nominal;
            }

            $validator = Validator::make([
                'kode_provinsi' => $kodeProvinsi,
                'nama_provinsi' => $namaProvinsi,
                'tahun' => $tahun,
                'nominal' => $nominal,
            ], [
                'kode_provinsi' => ['nullable', 'string', 'max:10'],
                'nama_provinsi' => ['nullable', 'string', 'max:100'],
                'tahun' => ['required', 'integer', 'min:1990', 'max:2100'],
                'nominal' => ['required', 'numeric', 'min:0'],
            ], [
                'tahun.required' => "Baris {$excelRow}: kolom tahun wajib diisi.",
                'tahun.integer' => "Baris {$excelRow}: kolom tahun harus berupa angka bulat.",
                'tahun.min' => "Baris {$excelRow}: tahun minimal 1990.",
                'tahun.max' => "Baris {$excelRow}: tahun maksimal 2100.",
                'nominal.required' => "Baris {$excelRow}: kolom nominal wajib diisi.",
                'nominal.numeric' => "Baris {$excelRow}: kolom nominal harus berupa angka.",
                'nominal.min' => "Baris {$excelRow}: kolom nominal tidak boleh negatif.",
            ]);

            if ($validator->fails()) {
                foreach ($validator->errors()->all() as $msg) {
                    $errors[] = $msg;
                }
                continue;
            }

            // Cari provinsi berdasarkan kode_provinsi atau nama_provinsi
            $provinsi = null;
            if ($kodeProvinsi !== '') {
                $provinsi = Provinsi::where('kode_provinsi', $kodeProvinsi)->first();
            }
            if (!$provinsi && $namaProvinsi !== '') {
                $provinsi = Provinsi::whereRaw('LOWER(nama_provinsi) = ?', [mb_strtolower($namaProvinsi)])->first();
            }

            if (!$provinsi) {
                $labelProvinsi = $kodeProvinsi !== '' ? $kodeProvinsi : $namaProvinsi;
                $errors[] = "Baris {$excelRow}: provinsi '{$labelProvinsi}' tidak ditemukan di database.";
                continue;
            }

            Ump::updateOrCreate(
                [
                    'provinsi_id' => $provinsi->id,
                    'tahun' => (int) $tahun,
                ],
                [
                    'nominal' => $nominal,
                ]
            );
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages([
                'import_errors' => $errors,
            ]);
        }
    }
}