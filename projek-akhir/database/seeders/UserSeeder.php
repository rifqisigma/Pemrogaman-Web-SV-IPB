<?php

namespace Database\Seeders;

use App\Models\Provinsi;
use App\Models\User;
use App\Models\WageLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin Account
        $admin = User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator National Wage',
                'email' => 'admin@nationalwage.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'provinsi_id' => Provinsi::where('kode_provinsi', '31')->value('id'),
                'bidang_kerja' => 'Pemerintahan & Kebijakan Publik',
                'jabatan' => 'Lead Data Analyst',
                'status_pekerjaan' => 'Tetap',
                'gaji_pokok' => 18500000,
            ]
        );

        // 2. 10 Regular Users with Wage Logs
        $userData = [
            [
                'username' => 'budi_santoso',
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'password' => Hash::make('password123'),
                'kode_provinsi' => '31', // DKI Jakarta
                'bidang_kerja' => 'Teknologi Informasi & Digital',
                'jabatan' => 'Senior Backend Engineer',
                'status_pekerjaan' => 'Tetap',
                'gaji_pokok' => 15500000,
                'logs' => [
                    [
                        'tahun' => 2024,
                        'gaji_sebelumnya' => 11000000,
                        'gaji_nominal' => 13000000,
                        'alasan' => 'Evaluasi performa tahunan dan penyesuaian pasar tech talent Jakarta',
                        'date' => '2024-03-15 09:30:00',
                    ],
                    [
                        'tahun' => 2025,
                        'gaji_sebelumnya' => 13000000,
                        'gaji_nominal' => 14500000,
                        'alasan' => 'Kenaikan upah rutin dan penyesuaian UMP DKI 2025',
                        'date' => '2025-01-20 14:15:00',
                    ],
                    [
                        'tahun' => 2026,
                        'gaji_sebelumnya' => 14500000,
                        'gaji_nominal' => 15500000,
                        'alasan' => 'Promosi jabatan dari Mid ke Senior Backend Engineer',
                        'date' => '2026-06-10 11:00:00',
                    ],
                ],
            ],
            [
                'username' => 'siti_rahma',
                'name' => 'Siti Rahmawati',
                'email' => 'siti.rahma@example.com',
                'password' => Hash::make('password123'),
                'kode_provinsi' => '32', // Jawa Barat
                'bidang_kerja' => 'Manufaktur & Otomotif',
                'jabatan' => 'Staff Quality Control',
                'status_pekerjaan' => 'Tetap',
                'gaji_pokok' => 5950000,
                'logs' => [
                    [
                        'tahun' => 2024,
                        'gaji_sebelumnya' => 4800000,
                        'gaji_nominal' => 5200000,
                        'alasan' => 'Penyesuaian kenaikan UMP/UMK kawasan industri Karawang-Bekasi',
                        'date' => '2024-01-10 08:45:00',
                    ],
                    [
                        'tahun' => 2025,
                        'gaji_sebelumnya' => 5200000,
                        'gaji_nominal' => 5600000,
                        'alasan' => 'Penetapan UMP baru Jawa Barat & insentif kehadiran',
                        'date' => '2025-02-14 10:20:00',
                    ],
                    [
                        'tahun' => 2026,
                        'gaji_sebelumnya' => 5600000,
                        'gaji_nominal' => 5950000,
                        'alasan' => 'Penambahan tunjangan sertifikasi standar ISO 9001',
                        'date' => '2026-05-02 16:30:00',
                    ],
                ],
            ],
            [
                'username' => 'andi_wijaya',
                'name' => 'Andi Pratama Wijaya',
                'email' => 'andi.wijaya@example.com',
                'password' => Hash::make('password123'),
                'kode_provinsi' => '35', // Jawa Timur
                'bidang_kerja' => 'Kesehatan & Farmasi',
                'jabatan' => 'Apoteker Penanggung Jawab Klinis',
                'status_pekerjaan' => 'Tetap',
                'gaji_pokok' => 7200000,
                'logs' => [
                    [
                        'tahun' => 2024,
                        'gaji_sebelumnya' => 5800000,
                        'gaji_nominal' => 6400000,
                        'alasan' => 'Kenaikan berkala rumah sakit swasta Surabaya',
                        'date' => '2024-04-12 13:00:00',
                    ],
                    [
                        'tahun' => 2025,
                        'gaji_sebelumnya' => 6400000,
                        'gaji_nominal' => 6900000,
                        'alasan' => 'Penyesuaian regulasi remunerasi tenaga kesehatan daerah Jawa Timur',
                        'date' => '2025-03-22 15:45:00',
                    ],
                    [
                        'tahun' => 2026,
                        'gaji_sebelumnya' => 6900000,
                        'gaji_nominal' => 7200000,
                        'alasan' => 'Penambahan beban koordinasi tim farmasi rawat inap',
                        'date' => '2026-08-19 09:15:00',
                    ],
                ],
            ],
            [
                'username' => 'dewi_lestari',
                'name' => 'Dewi Lestari',
                'email' => 'dewi.lestari@example.com',
                'password' => Hash::make('password123'),
                'kode_provinsi' => '34', // DI Yogyakarta
                'bidang_kerja' => 'Pendidikan & Riset Ilmiah',
                'jabatan' => 'Peneliti & Dosen Non-PNS',
                'status_pekerjaan' => 'Kontrak',
                'gaji_pokok' => 4600000,
                'logs' => [
                    [
                        'tahun' => 2024,
                        'gaji_sebelumnya' => 3600000,
                        'gaji_nominal' => 4000000,
                        'alasan' => 'Penyesuaian batas UMP DIY dan kontrak baru universitas',
                        'date' => '2024-02-18 11:10:00',
                    ],
                    [
                        'tahun' => 2025,
                        'gaji_sebelumnya' => 4000000,
                        'gaji_nominal' => 4300000,
                        'alasan' => 'Kenaikan UMP DIY tahun 2025 dan tunjangan publikasi jurnal',
                        'date' => '2025-01-28 14:00:00',
                    ],
                    [
                        'tahun' => 2026,
                        'gaji_sebelumnya' => 4300000,
                        'gaji_nominal' => 4600000,
                        'alasan' => 'Perpanjangan kontrak tahun ke-3 dengan kenaikan grade penelitian',
                        'date' => '2026-07-04 10:40:00',
                    ],
                ],
            ],
            [
                'username' => 'eka_saputra',
                'name' => 'Eka Saputra',
                'email' => 'eka.saputra@example.com',
                'password' => Hash::make('password123'),
                'kode_provinsi' => '51', // Bali
                'bidang_kerja' => 'Pariwisata & Hospitaliti',
                'jabatan' => 'Front Office Assistant Manager',
                'status_pekerjaan' => 'Tetap',
                'gaji_pokok' => 6100000,
                'logs' => [
                    [
                        'tahun' => 2024,
                        'gaji_sebelumnya' => 4500000,
                        'gaji_nominal' => 5200000,
                        'alasan' => 'Pemulihan pasca pandemi & penyesuaian UMP Bali sektor perhotelan bintang 5',
                        'date' => '2024-05-10 17:00:00',
                    ],
                    [
                        'tahun' => 2025,
                        'gaji_sebelumnya' => 5200000,
                        'gaji_nominal' => 5700000,
                        'alasan' => 'Penyesuaian UMP Bali dan kenaikan service charge pokok',
                        'date' => '2025-04-16 11:25:00',
                    ],
                    [
                        'tahun' => 2026,
                        'gaji_sebelumnya' => 5700000,
                        'gaji_nominal' => 6100000,
                        'alasan' => 'Promosi sebagai Assistant Manager resort Nusa Dua',
                        'date' => '2026-09-01 13:30:00',
                    ],
                ],
            ],
            [
                'username' => 'fajar_ramadhan',
                'name' => 'Fajar Ramadhan',
                'email' => 'fajar.ramadhan@example.com',
                'password' => Hash::make('password123'),
                'kode_provinsi' => '64', // Kalimantan Timur
                'bidang_kerja' => 'Pertambangan & Energi Terbarukan',
                'jabatan' => 'Site Safety Specialist (HSE)',
                'status_pekerjaan' => 'Tetap',
                'gaji_pokok' => 14200000,
                'logs' => [
                    [
                        'tahun' => 2024,
                        'gaji_sebelumnya' => 10500000,
                        'gaji_nominal' => 12000000,
                        'alasan' => 'Penetapan upah minimum sektoral migas & tambang Kaltim',
                        'date' => '2024-06-20 09:00:00',
                    ],
                    [
                        'tahun' => 2025,
                        'gaji_sebelumnya' => 12000000,
                        'gaji_nominal' => 13100000,
                        'alasan' => 'Penyesuaian dampak IKN & kenaikan indeks biaya hidup Balikpapan',
                        'date' => '2025-05-18 10:15:00',
                    ],
                    [
                        'tahun' => 2026,
                        'gaji_sebelumnya' => 13100000,
                        'gaji_nominal' => 14200000,
                        'alasan' => 'Sertifikasi Ahli K3 Utama & tanggung jawab proyek ekspansi',
                        'date' => '2026-08-11 15:50:00',
                    ],
                ],
            ],
            [
                'username' => 'gita_paramita',
                'name' => 'Gita Paramita',
                'email' => 'gita.paramita@example.com',
                'password' => Hash::make('password123'),
                'kode_provinsi' => '12', // Sumatera Utara
                'bidang_kerja' => 'Perbankan & Lembaga Keuangan',
                'jabatan' => 'Credit Analyst Komersial',
                'status_pekerjaan' => 'Tetap',
                'gaji_pokok' => 7900000,
                'logs' => [
                    [
                        'tahun' => 2024,
                        'gaji_sebelumnya' => 6200000,
                        'gaji_nominal' => 6800000,
                        'alasan' => 'Kenaikan berkala tahunan bank nasional cabang Medan',
                        'date' => '2024-07-08 11:30:00',
                    ],
                    [
                        'tahun' => 2025,
                        'gaji_sebelumnya' => 6800000,
                        'gaji_nominal' => 7400000,
                        'alasan' => 'Kenaikan UMP Sumatera Utara dan pencapaian KPI target kredit UMKM',
                        'date' => '2025-06-15 14:40:00',
                    ],
                    [
                        'tahun' => 2026,
                        'gaji_sebelumnya' => 7400000,
                        'gaji_nominal' => 7900000,
                        'alasan' => 'Kenaikan grade analis komersial level II',
                        'date' => '2026-10-12 16:10:00',
                    ],
                ],
            ],
            [
                'username' => 'hendra_gunawan',
                'name' => 'Hendra Gunawan',
                'email' => 'hendra.gunawan@example.com',
                'password' => Hash::make('password123'),
                'kode_provinsi' => '73', // Sulawesi Selatan
                'bidang_kerja' => 'Logistik & Transportasi Rantai Pasok',
                'jabatan' => 'Supply Chain Supervisor Makassar Hub',
                'status_pekerjaan' => 'Tetap',
                'gaji_pokok' => 6500000,
                'logs' => [
                    [
                        'tahun' => 2024,
                        'gaji_sebelumnya' => 5000000,
                        'gaji_nominal' => 5500000,
                        'alasan' => 'Penyesuaian kenaikan UMP Sulsel dan tunjangan shift logistik pelabuhan',
                        'date' => '2024-08-01 10:00:00',
                    ],
                    [
                        'tahun' => 2025,
                        'gaji_sebelumnya' => 5500000,
                        'gaji_nominal' => 6000000,
                        'alasan' => 'Penyesuaian UMP regional Sulawesi Selatan tahun 2025',
                        'date' => '2025-07-22 13:10:00',
                    ],
                    [
                        'tahun' => 2026,
                        'gaji_sebelumnya' => 6000000,
                        'gaji_nominal' => 6500000,
                        'alasan' => 'Efisiensi rute Indonesia Timur & apresiasi manajemen',
                        'date' => '2026-09-30 14:20:00',
                    ],
                ],
            ],
            [
                'username' => 'intan_permata',
                'name' => 'Intan Permata',
                'email' => 'intan.permata@example.com',
                'password' => Hash::make('password123'),
                'kode_provinsi' => '36', // Banten
                'bidang_kerja' => 'Ritel Modern & E-Commerce FMCG',
                'jabatan' => 'Area Store Supervisor Tangerang',
                'status_pekerjaan' => 'Tetap',
                'gaji_pokok' => 7400000,
                'logs' => [
                    [
                        'tahun' => 2024,
                        'gaji_sebelumnya' => 5800000,
                        'gaji_nominal' => 6300000,
                        'alasan' => 'Penyesuaian upah minimum regional Banten & bonus pembukaan toko baru',
                        'date' => '2024-09-11 09:20:00',
                    ],
                    [
                        'tahun' => 2025,
                        'gaji_sebelumnya' => 6300000,
                        'gaji_nominal' => 6850000,
                        'alasan' => 'Kenaikan tahunan dan penyesuaian UMP Banten 2025',
                        'date' => '2025-08-14 11:35:00',
                    ],
                    [
                        'tahun' => 2026,
                        'gaji_sebelumnya' => 6850000,
                        'gaji_nominal' => 7400000,
                        'alasan' => 'Promosi memegang wilayah supervisi 8 gerai di Banten utara',
                        'date' => '2026-10-25 15:00:00',
                    ],
                ],
            ],
            [
                'username' => 'joko_susilo',
                'name' => 'Joko Susilo',
                'email' => 'joko.susilo@example.com',
                'password' => Hash::make('password123'),
                'kode_provinsi' => '33', // Jawa Tengah
                'bidang_kerja' => 'Konstruksi & Infrastruktur Sipil',
                'jabatan' => 'Site Civil Drafter & Pelaksana',
                'status_pekerjaan' => 'Kontrak',
                'gaji_pokok' => 4850000,
                'logs' => [
                    [
                        'tahun' => 2024,
                        'gaji_sebelumnya' => 3700000,
                        'gaji_nominal' => 4100000,
                        'alasan' => 'Penyesuaian kenaikan UMP Jawa Tengah kawasan industri Kendal/Semarang',
                        'date' => '2024-10-05 08:30:00',
                    ],
                    [
                        'tahun' => 2025,
                        'gaji_sebelumnya' => 4100000,
                        'gaji_nominal' => 4450000,
                        'alasan' => 'Penetapan UMP Jawa Tengah tahun 2025 dan lembur proyek jalan tol',
                        'date' => '2025-09-19 12:15:00',
                    ],
                    [
                        'tahun' => 2026,
                        'gaji_sebelumnya' => 4450000,
                        'gaji_nominal' => 4850000,
                        'alasan' => 'Perpanjangan kontrak proyek strategis nasional & tunjangan operasional',
                        'date' => '2026-11-05 16:45:00',
                    ],
                ],
            ],
        ];

        foreach ($userData as $u) {
            $provinsi = Provinsi::where('kode_provinsi', $u['kode_provinsi'])->first();

            $user = User::updateOrCreate(
                ['username' => $u['username']],
                [
                    'name' => $u['name'],
                    'email' => $u['email'],
                    'password' => $u['password'],
                    'role' => 'user',
                    'provinsi_id' => $provinsi ? $provinsi->id : null,
                    'bidang_kerja' => $u['bidang_kerja'],
                    'jabatan' => $u['jabatan'],
                    'status_pekerjaan' => $u['status_pekerjaan'],
                    'gaji_pokok' => $u['gaji_pokok'],
                ]
            );

            // Seed logs for this user
            foreach ($u['logs'] as $log) {
                WageLog::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'tahun' => $log['tahun'],
                        'alasan_pembaruan' => $log['alasan'],
                    ],
                    [
                        'provinsi_id' => $user->provinsi_id,
                        'bidang_kerja' => $user->bidang_kerja,
                        'jabatan' => $user->jabatan,
                        'status_pekerjaan' => $user->status_pekerjaan,
                        'gaji_nominal' => $log['gaji_nominal'],
                        'gaji_sebelumnya' => $log['gaji_sebelumnya'],
                        'ip_address' => '127.0.0.1',
                        'created_at' => Carbon::parse($log['date']),
                        'updated_at' => Carbon::parse($log['date']),
                    ]
                );
            }
        }
    }
}
