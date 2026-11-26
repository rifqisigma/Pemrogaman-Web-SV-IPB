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
        '11' => ['lat' => 4.695135, 'lng' => 96.749399],
        '12' => ['lat' => 2.115355, 'lng' => 99.545097],
        '13' => ['lat' => -0.73994, 'lng' => 100.800005],
        '14' => ['lat' => 0.293347, 'lng' => 101.706829],
        '15' => ['lat' => -1.485183, 'lng' => 102.438058],
        '16' => ['lat' => -3.319437, 'lng' => 104.914399],
        '17' => ['lat' => -3.577847, 'lng' => 102.346388],
        '18' => ['lat' => -4.558585, 'lng' => 105.406808],
        '19' => ['lat' => -2.741051, 'lng' => 106.440587],
        '21' => ['lat' => 3.945651, 'lng' => 108.142867],
        '31' => ['lat' => -6.208763, 'lng' => 106.845599],
        '32' => ['lat' => -6.88919, 'lng' => 107.640472],
        '33' => ['lat' => -7.150975, 'lng' => 110.140259],
        '34' => ['lat' => -7.875385, 'lng' => 110.426209],
        '35' => ['lat' => -7.536064, 'lng' => 112.238402],
        '36' => ['lat' => -6.405817, 'lng' => 106.064018],
        '51' => ['lat' => -8.409518, 'lng' => 115.188916],
        '52' => ['lat' => -8.652933, 'lng' => 117.361648],
        '53' => ['lat' => -8.657382, 'lng' => 121.079371],
        '61' => ['lat' => -0.278781, 'lng' => 111.475285],
        '62' => ['lat' => -1.681488, 'lng' => 113.382355],
        '63' => ['lat' => -3.092642, 'lng' => 115.283759],
        '64' => ['lat' => 0.538659, 'lng' => 116.419389],
        '65' => ['lat' => 3.073093, 'lng' => 116.041388],
        '71' => ['lat' => 0.624693, 'lng' => 123.975002],
        '72' => ['lat' => -1.430025, 'lng' => 121.445618],
        '73' => ['lat' => -3.668799, 'lng' => 119.974053],
        '74' => ['lat' => -4.14491, 'lng' => 122.174605],
        '75' => ['lat' => 0.699937, 'lng' => 122.446724],
        '76' => ['lat' => -2.844137, 'lng' => 119.232078],
        '81' => ['lat' => -3.238462, 'lng' => 130.145273],
        '82' => ['lat' => 1.570999, 'lng' => 127.808769],
        '91' => ['lat' => -4.269928, 'lng' => 138.080353],
        '92' => ['lat' => -1.336115, 'lng' => 133.174716],
        '93' => ['lat' => -7.500000, 'lng' => 139.500000],
        '94' => ['lat' => -3.800000, 'lng' => 136.500000],
        '95' => ['lat' => -4.100000, 'lng' => 139.000000],
        '96' => ['lat' => -0.900000, 'lng' => 131.300000],
    ];

    public function index(Request $request)
    {
        $years = Ump::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $year = (int) $request->input('tahun', $years->first() ?? 2026);

        $payload = $this->getDashboardData($year);

        return view('welcome', array_merge($payload, [
            'years' => $years,
            'year' => $year,
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

        $current = Ump::with('provinsi')->where('tahun', $year)->get();
        $prev = Ump::where('tahun', $prevYear)->pluck('nominal', 'provinsi_id');

        $provinces = [];
        $total = 0;
        $count = $current->count();

        foreach ($current as $ump) {
            $prov = $ump->provinsi;
            $code = $prov->kode_provinsi ?? '';
            $coord = $this->coords[$code] ?? ['lat' => -2.5, 'lng' => 118.0];
            $prevNominal = $prev[$ump->provinsi_id] ?? null;

            $growth = null;
            if ($prevNominal && $prevNominal > 0) {
                $growth = round((($ump->nominal - $prevNominal) / $prevNominal) * 100, 2);
            }

            $userWageAvg = WageLog::where('provinsi_id', $ump->provinsi_id)->avg('gaji_nominal');
            $userWageCount = WageLog::where('provinsi_id', $ump->provinsi_id)->count();

            $total += $ump->nominal;

            $provinces[] = [
                'id' => $ump->provinsi_id,
                'kode' => $code,
                'nama' => $prov->nama_provinsi ?? 'Unknown',
                'nominal' => (float) $ump->nominal,
                'formatted' => 'Rp ' . number_format($ump->nominal, 0, ',', '.'),
                'growth' => $growth,
                'reported_avg' => $userWageAvg ? (float) $userWageAvg : null,
                'reported_formatted' => $userWageAvg ? 'Rp ' . number_format($userWageAvg, 0, ',', '.') : 'Belum ada data',
                'reported_count' => $userWageCount,
                'lat' => $coord['lat'],
                'lng' => $coord['lng'],
            ];
        }

        usort($provinces, fn($a, $b) => $b['nominal'] <=> $a['nominal']);

        $highest = $provinces[0] ?? null;
        $lowest = !empty($provinces) ? $provinces[count($provinces) - 1] : null;
        $avg = $count > 0 ? round($total / $count) : 0;

        $prevAvg = Ump::where('tahun', $prevYear)->avg('nominal');
        $growth = ($prevAvg && $prevAvg > 0)
            ? round((($avg - $prevAvg) / $prevAvg) * 100, 2)
            : 0;

        $top = array_slice($provinces, 0, 5);
        $bottom = array_reverse(array_slice($provinces, -5));

        $trend = Ump::select('tahun', DB::raw('AVG(nominal) as avg_nominal'))
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

        $sectors = WageLog::select('bidang_kerja', DB::raw('AVG(gaji_nominal) as avg_gaji'), DB::raw('COUNT(*) as total_pekerja'))
            ->groupBy('bidang_kerja')
            ->orderBy('avg_gaji', 'desc')
            ->limit(6)
            ->get();

        $workersCount = WageLog::count();
        $workersAvg = round(WageLog::avg('gaji_nominal') ?? 0);

        return [
            'provinces' => $provinces,
            'highest' => $highest,
            'lowest' => $lowest,
            'avg' => $avg,
            'growth' => $growth,
            'top' => $top,
            'bottom' => $bottom,
            'trend' => $trend,
            'sectors' => $sectors,
            'workersCount' => $workersCount,
            'workersAvg' => $workersAvg,
        ];
    }
}
