<?php

namespace Database\Seeders;

use App\Models\Provinsi;
use App\Models\Ump;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class UmpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $apiKey = config('services.apiindonesia.key');
        if (! is_string($apiKey) || trim($apiKey) === '') {
            $this->command->error('API_INDONESIA_KEY belum diatur.');

            return;
        }

        $apiUrl = rtrim(config('services.apiindonesia.url'), '/').'/ump';
        $this->command->info("Mengambil data UMP dari: {$apiUrl}");

        $page = 1;
        $totalPages = 1;
        $berhasil = 0;

        do {
            $response = Http::withHeaders(['x-api-key' => $apiKey])
                ->acceptJson()
                ->timeout(30)
                ->get($apiUrl, ['per_page' => 100, 'page' => $page]);

            if ($response->failed()) {
                $this->command->error('Gagal mengambil data dari API. HTTP Status: '.$response->status());

                return;
            }

            $payload = $response->json();
            if (! is_array($payload)) {
                $this->command->error('Format response JSON tidak sesuai.');

                return;
            }

            $items = $payload['data'] ?? null;

            if (! is_array($items)) {
                $this->command->error('Format response JSON tidak sesuai.');

                return;
            }

            foreach ($items as $item) {
                $kodeProvinsi = $item['province_code'] ?? null;
                $namaProvinsi = $item['province_name'] ?? $item['provinsi'] ?? $item['nama_provinsi'] ?? $item['province'] ?? null;
                $tahun = $item['year'] ?? $item['tahun'] ?? date('Y');
                $rawNominal = $item['salary'] ?? $item['nominal'] ?? $item['ump'] ?? $item['upah'] ?? 0;
                $nominal = (float) preg_replace('/[^0-9]/', '', (string) $rawNominal);

                if (! $namaProvinsi || $nominal <= 0) {
                    continue;
                }

                $provinsi = $kodeProvinsi
                    ? Provinsi::where('kode_provinsi', $kodeProvinsi)->first()
                    : null;

                if (! $provinsi) {
                    $provinsi = Provinsi::whereRaw('LOWER(nama_provinsi) = ?', [strtolower(trim($namaProvinsi))])
                        ->first();
                }

                if (! $provinsi) {
                    $cleanName = str_replace(['.', 'Provinsi '], '', trim($namaProvinsi));
                    $provinsi = Provinsi::where('nama_provinsi', 'LIKE', "%{$cleanName}%")->first();
                }

                if ($provinsi) {
                    Ump::updateOrCreate(
                        [
                            'provinsi_id' => $provinsi->id,
                            'tahun' => (int) $tahun,
                        ],
                        [
                            'nominal' => $nominal,
                        ]
                    );
                    $berhasil++;
                } else {
                    $this->command->warn("Provinsi tidak ditemukan di database: {$namaProvinsi}");
                }
            }

            $totalPages = max(1, (int) data_get($payload, 'meta.total_pages', 1));
            $page++;
        } while ($page <= $totalPages);

        $this->command->info("Selesai! Berhasil menyimpan/memperbarui {$berhasil} data UMP.");
    }
}
