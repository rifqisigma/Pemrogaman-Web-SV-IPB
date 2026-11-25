<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Ump;
use App\Models\WageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    private array $coords = [
        '11' => ['lat' => 4.695135, 'lng' => 96.749399], // Aceh
        '12' => ['lat' => 2.115355, 'lng' => 99.545097], // Sumatera Utara
        '13' => ['lat' => -0.73994, 'lng' => 100.800005], // Sumatera Barat
        '14' => ['lat' => 0.293347, 'lng' => 101.706829], // Riau
        '15' => ['lat' => -1.485183, 'lng' => 102.438058], // Jambi
        '16' => ['lat' => -3.319437, 'lng' => 104.914399], // Sumatera Selatan
        '17' => ['lat' => -3.577847, 'lng' => 102.346388], // Bengkulu
        '18' => ['lat' => -4.558585, 'lng' => 105.406808], // Lampung
        '19' => ['lat' => -2.741051, 'lng' => 106.440587], // Bangka Belitung
        '21' => ['lat' => 3.945651, 'lng' => 108.142867], // Kepulauan Riau
        '31' => ['lat' => -6.208763, 'lng' => 106.845599], // DKI Jakarta
        '32' => ['lat' => -6.88919, 'lng' => 107.640472], // Jawa Barat
        '33' => ['lat' => -7.150975, 'lng' => 110.140259], // Jawa Tengah
        '34' => ['lat' => -7.875385, 'lng' => 110.426209], // DI Yogyakarta
        '35' => ['lat' => -7.536064, 'lng' => 112.238402], // Jawa Timur
        '36' => ['lat' => -6.405817, 'lng' => 106.064018], // Banten
        '51' => ['lat' => -8.409518, 'lng' => 115.188916], // Bali
        '52' => ['lat' => -8.652933, 'lng' => 117.361648], // NTB
        '53' => ['lat' => -8.657382, 'lng' => 121.079371], // NTT
        '61' => ['lat' => -0.278781, 'lng' => 111.475285], // Kalbar
        '62' => ['lat' => -1.681488, 'lng' => 113.382355], // Kalteng
        '63' => ['lat' => -3.092642, 'lng' => 115.283759], // Kalsel
        '64' => ['lat' => 0.538659, 'lng' => 116.419389], // Kaltim
        '65' => ['lat' => 3.073093, 'lng' => 116.041388], // Kaltara
        '71' => ['lat' => 0.624693, 'lng' => 123.975002], // Sulut
        '72' => ['lat' => -1.430025, 'lng' => 121.445618], // Sulteng
        '73' => ['lat' => -3.668799, 'lng' => 119.974053], // Sulsel
        '74' => ['lat' => -4.14491, 'lng' => 122.174605], // Sultra
        '75' => ['lat' => 0.699937, 'lng' => 122.446724], // Gorontalo
        '76' => ['lat' => -2.844137, 'lng' => 119.232078], // Sulbar
        '81' => ['lat' => -3.238462, 'lng' => 130.145273], // Maluku
        '82' => ['lat' => 1.570999, 'lng' => 127.808769], // Malut
        '91' => ['lat' => -4.269928, 'lng' => 138.080353], // Papua
        '92' => ['lat' => -1.336115, 'lng' => 133.174716], // Papua Barat
        '93' => ['lat' => -7.500000, 'lng' => 139.500000], // Papua Selatan
        '94' => ['lat' => -3.800000, 'lng' => 136.500000], // Papua Tengah
        '95' => ['lat' => -4.100000, 'lng' => 139.000000], // Papua Pegunungan
        '96' => ['lat' => -0.900000, 'lng' => 131.300000], // Papua Barat Daya
    ];

    public function index(Request $request)
    {
        $availableYears = Ump::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $selectedYear = (int) $request->input('tahun', $availableYears->first() ?? 2026);

        $data = $this->getDashboardData($selectedYear);

        return view('welcome', array_merge($data, [
            'availableYears' => $availableYears,
            'selectedYear' => $selectedYear,
        ]));
    }

    public function apiData(Request $request)
    {
        $year = (int) $request->input('tahun', 2026);
        return response()->json($this->getDashboardData($year));
    }

    private function getDashboardData(int $year): array
    {
        $prevYear = $year - 1;

        // Fetch UMP for current year
        $currentUmps = Ump::with('provinsi')
            ->where('tahun', $year)
            ->get();

        // Fetch UMP for prev year for growth calculation
        $prevUmps = Ump::where('tahun', $prevYear)->pluck('nominal', 'provinsi_id');

        $provinsiData = [];
        $totalNominal = 0;
        $count = $currentUmps->count();

        foreach ($currentUmps as $ump) {
            $prov = $ump->provinsi;
            $kode = $prov->kode_provinsi ?? '';
            $coord = $this->coords[$kode] ?? ['lat' => -2.5, 'lng' => 118.0];
            $prevNominal = $prevUmps[$ump->provinsi_id] ?? null;

            $growth = null;
            if ($prevNominal && $prevNominal > 0) {
                $growth = round((($ump->nominal - $prevNominal) / $prevNominal) * 100, 2);
            }

            // Count user samples in this province
            $reportedWageAvg = WageLog::where('provinsi_id', $ump->provinsi_id)->avg('gaji_nominal');
            $reportedCount = WageLog::where('provinsi_id', $ump->provinsi_id)->count();

            $totalNominal += $ump->nominal;

            $provinsiData[] = [
                'id' => $ump->provinsi_id,
                'kode' => $kode,
                'nama' => $prov->nama_provinsi ?? 'Unknown',
                'nominal' => (float) $ump->nominal,
                'formatted' => 'Rp ' . number_format($ump->nominal, 0, ',', '.'),
                'growth' => $growth,
                'reported_avg' => $reportedWageAvg ? (float) $reportedWageAvg : null,
                'reported_formatted' => $reportedWageAvg ? 'Rp ' . number_format($reportedWageAvg, 0, ',', '.') : 'Belum ada data',
                'reported_count' => $reportedCount,
                'lat' => $coord['lat'],
                'lng' => $coord['lng'],
            ];
        }

        // Sort for rankings
        usort($provinsiData, fn($a, $b) => $b['nominal'] <=> $a['nominal']);

        $highest = $provinsiData[0] ?? null;
        $lowest = !empty($provinsiData) ? $provinsiData[count($provinsiData) - 1] : null;
        $nationalAverage = $count > 0 ? round($totalNominal / $count) : 0;

        // National growth YoY
        $prevNationalAvg = Ump::where('tahun', $prevYear)->avg('nominal');
        $nationalGrowth = ($prevNationalAvg && $prevNationalAvg > 0)
            ? round((($nationalAverage - $prevNationalAvg) / $prevNationalAvg) * 100, 2)
            : 0;

        // Top 5 and Bottom 5
        $top5 = array_slice($provinsiData, 0, 5);
        $bottom5 = array_slice($provinsiData, -5);
        $bottom5 = array_reverse($bottom5); // lowest first

        // Historical trend for line chart (last 8 years)
        $historicalTrend = Ump::select('tahun', DB::raw('AVG(nominal) as avg_nominal'))
            ->where('tahun', '>=', max(1997, $year - 7))
            ->where('tahun', '<=', $year)
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->get()
            ->map(fn($row) => [
                'tahun' => (int) $row->tahun,
                'avg_nominal' => round((float) $row->avg_nominal),
                'formatted' => 'Rp ' . number_format($row->avg_nominal, 0, ',', '.'),
            ]);

        // Sektor Breakdown from WageLog
        $sectorStats = WageLog::select('bidang_kerja', DB::raw('AVG(gaji_nominal) as avg_gaji'), DB::raw('COUNT(*) as total_pekerja'))
            ->groupBy('bidang_kerja')
            ->orderBy('avg_gaji', 'desc')
            ->limit(6)
            ->get();

        $totalReportedWorkers = WageLog::count();
        $overallWorkerAvg = WageLog::avg('gaji_nominal') ?? 0;

        return [
            'provinsiData' => $provinsiData,
            'highest' => $highest,
            'lowest' => $lowest,
            'nationalAverage' => $nationalAverage,
            'nationalGrowth' => $nationalGrowth,
            'top5' => $top5,
            'bottom5' => $bottom5,
            'historicalTrend' => $historicalTrend,
            'sectorStats' => $sectorStats,
            'totalReportedWorkers' => $totalReportedWorkers,
            'overallWorkerAvg' => round($overallWorkerAvg),
        ];
    }
}
