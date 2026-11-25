<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Ump;
use App\Models\WageLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceApiController extends Controller
{
    /**
     * Handle incoming request for /service-api.
     * If user visits in browser (expects HTML), render interactive documentation & tester.
     * If user requests JSON (API client, curl, ?format=json), return flexible filtered JSON.
     */
    public function index(Request $request)
    {
        if ($request->wantsJson() || $request->is('api/*') || $request->query('format') === 'json' || $request->ajax()) {
            return $this->getFlexibleData($request);
        }

        // Available metadata for documentation view
        $provinsis = Provinsi::orderBy('nama_provinsi')->get(['id', 'kode_provinsi', 'nama_provinsi']);
        $years = Ump::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $sectors = WageLog::select('bidang_kerja')->distinct()->orderBy('bidang_kerja')->pluck('bidang_kerja');

        return view('service-api.index', compact('provinsis', 'years', 'sectors'));
    }

    /**
     * Flexible GET API endpoint providing filtered wage data.
     */
    public function getFlexibleData(Request $request): JsonResponse
    {
        $type = strtolower((string) $request->input('type', 'ump'));
        $tahun = $request->filled('tahun') ? (int) $request->input('tahun') : null;
        $tahunDari = $request->filled('tahun_dari') ? (int) $request->input('tahun_dari') : null;
        $tahunSampai = $request->filled('tahun_sampai') ? (int) $request->input('tahun_sampai') : null;
        $provinsi = $request->input('provinsi');
        $kodeProvinsi = $request->input('kode_provinsi');
        $provinsiId = $request->filled('provinsi_id') ? (int) $request->input('provinsi_id') : null;
        $sektor = $request->input('bidang_kerja') ?? $request->input('sektor');
        $minNominal = $request->filled('min_nominal') ? (float) $request->input('min_nominal') : null;
        $maxNominal = $request->filled('max_nominal') ? (float) $request->input('max_nominal') : null;
        $sort = strtolower((string) $request->input('sort', 'tahun_desc'));
        $perPage = min(100, max(1, (int) $request->input('limit', $request->input('per_page', 25))));
        $page = max(1, (int) $request->input('page', 1));

        $filtersApplied = array_filter([
            'type' => $type,
            'tahun' => $tahun,
            'tahun_dari' => $tahunDari,
            'tahun_sampai' => $tahunSampai,
            'provinsi' => $provinsi,
            'kode_provinsi' => $kodeProvinsi,
            'provinsi_id' => $provinsiId,
            'sektor' => $sektor,
            'min_nominal' => $minNominal,
            'max_nominal' => $maxNominal,
            'sort' => $sort,
            'per_page' => $perPage,
            'page' => $page,
        ]);

        // Branch 1: UMP Official Minimum Wage
        if ($type === 'ump') {
            $query = Ump::with('provinsi');

            if ($tahun) {
                $query->where('tahun', $tahun);
            }
            if ($tahunDari) {
                $query->where('tahun', '>=', $tahunDari);
            }
            if ($tahunSampai) {
                $query->where('tahun', '<=', $tahunSampai);
            }
            if ($provinsiId) {
                $query->where('provinsi_id', $provinsiId);
            }
            if ($kodeProvinsi) {
                $query->whereHas('provinsi', fn($q) => $q->where('kode_provinsi', $kodeProvinsi));
            }
            if ($provinsi) {
                $query->whereHas('provinsi', fn($q) => $q->where('nama_provinsi', 'like', "%{$provinsi}%"));
            }
            if ($minNominal !== null) {
                $query->where('nominal', '>=', $minNominal);
            }
            if ($maxNominal !== null) {
                $query->where('nominal', '<=', $maxNominal);
            }

            // Sorting
            match ($sort) {
                'nominal_desc' => $query->orderBy('nominal', 'desc'),
                'nominal_asc' => $query->orderBy('nominal', 'asc'),
                'tahun_asc' => $query->orderBy('tahun', 'asc')->orderBy('nominal', 'desc'),
                'nama_asc' => $query->join('provinsis', 'provinsis.id', '=', 'umps.provinsi_id')->orderBy('provinsis.nama_provinsi', 'asc')->select('umps.*'),
                default => $query->orderBy('tahun', 'desc')->orderBy('nominal', 'desc'),
            };

            $paginator = $query->paginate($perPage, ['*'], 'page', $page);

            $data = $paginator->getCollection()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'ump_resmi',
                    'provinsi' => [
                        'id' => $item->provinsi_id,
                        'kode' => $item->provinsi->kode_provinsi ?? null,
                        'nama' => $item->provinsi->nama_provinsi ?? null,
                    ],
                    'tahun' => (int) $item->tahun,
                    'nominal' => (float) $item->nominal,
                    'nominal_formatted' => 'Rp ' . number_format($item->nominal, 0, ',', '.'),
                    'last_updated' => $item->updated_at ? $item->updated_at->toIso8601String() : null,
                ];
            });

            return response()->json([
                'status' => 'success',
                'meta' => [
                    'endpoint' => '/service-api',
                    'timestamp' => now()->toIso8601String(),
                    'total_results' => $paginator->total(),
                    'page' => $paginator->currentPage(),
                    'per_page' => $paginator->perPage(),
                    'total_pages' => $paginator->lastPage(),
                    'filters_applied' => $filtersApplied,
                ],
                'data' => $data,
            ]);
        }

        // Branch 2: Worker / Wage Logs (Data Upah Pekerja Riil)
        if ($type === 'pekerja' || $type === 'worker') {
            $query = WageLog::with('provinsi');

            if ($tahun) {
                $query->where('tahun', $tahun);
            }
            if ($tahunDari) {
                $query->where('tahun', '>=', $tahunDari);
            }
            if ($tahunSampai) {
                $query->where('tahun', '<=', $tahunSampai);
            }
            if ($provinsiId) {
                $query->where('provinsi_id', $provinsiId);
            }
            if ($kodeProvinsi) {
                $query->whereHas('provinsi', fn($q) => $q->where('kode_provinsi', $kodeProvinsi));
            }
            if ($provinsi) {
                $query->whereHas('provinsi', fn($q) => $q->where('nama_provinsi', 'like', "%{$provinsi}%"));
            }
            if ($sektor) {
                $query->where('bidang_kerja', 'like', "%{$sektor}%");
            }
            if ($minNominal !== null) {
                $query->where('gaji_nominal', '>=', $minNominal);
            }
            if ($maxNominal !== null) {
                $query->where('gaji_nominal', '<=', $maxNominal);
            }

            match ($sort) {
                'nominal_desc' => $query->orderBy('gaji_nominal', 'desc'),
                'nominal_asc' => $query->orderBy('gaji_nominal', 'asc'),
                'tahun_asc' => $query->orderBy('tahun', 'asc'),
                default => $query->orderBy('created_at', 'desc'),
            };

            $paginator = $query->paginate($perPage, ['*'], 'page', $page);

            $data = $paginator->getCollection()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'upah_pekerja',
                    'provinsi' => [
                        'id' => $item->provinsi_id,
                        'kode' => $item->provinsi->kode_provinsi ?? null,
                        'nama' => $item->provinsi->nama_provinsi ?? null,
                    ],
                    'bidang_kerja' => $item->bidang_kerja,
                    'jabatan' => $item->jabatan,
                    'status_pekerjaan' => $item->status_pekerjaan,
                    'tahun' => (int) $item->tahun,
                    'gaji_nominal' => (float) $item->gaji_nominal,
                    'gaji_formatted' => 'Rp ' . number_format($item->gaji_nominal, 0, ',', '.'),
                    'gaji_sebelumnya' => $item->gaji_sebelumnya ? (float) $item->gaji_sebelumnya : null,
                    'alasan_pembaruan' => $item->alasan_pembaruan,
                    'recorded_at' => $item->created_at ? $item->created_at->toIso8601String() : null,
                ];
            });

            return response()->json([
                'status' => 'success',
                'meta' => [
                    'endpoint' => '/service-api',
                    'timestamp' => now()->toIso8601String(),
                    'total_results' => $paginator->total(),
                    'page' => $paginator->currentPage(),
                    'per_page' => $paginator->perPage(),
                    'total_pages' => $paginator->lastPage(),
                    'filters_applied' => $filtersApplied,
                ],
                'data' => $data,
            ]);
        }

        // Branch 3: Combined / Summary
        $targetYear = $tahun ?? Ump::max('tahun') ?? 2026;
        $umpStats = Ump::where('tahun', $targetYear)->select(
            DB::raw('COUNT(*) as total_provinsi'),
            DB::raw('AVG(nominal) as rerata_ump'),
            DB::raw('MAX(nominal) as max_ump'),
            DB::raw('MIN(nominal) as min_ump')
        )->first();

        $workerStats = WageLog::select(
            DB::raw('COUNT(*) as total_sampel'),
            DB::raw('AVG(gaji_nominal) as rerata_gaji'),
            DB::raw('MAX(gaji_nominal) as max_gaji'),
            DB::raw('MIN(gaji_nominal) as min_gaji')
        )->first();

        return response()->json([
            'status' => 'success',
            'meta' => [
                'endpoint' => '/service-api',
                'timestamp' => now()->toIso8601String(),
                'type' => 'summary_overview',
                'target_year' => $targetYear,
                'filters_applied' => $filtersApplied,
            ],
            'data' => [
                'ump_resmi' => [
                    'tahun' => $targetYear,
                    'total_provinsi' => (int) ($umpStats->total_provinsi ?? 0),
                    'rerata_nasional' => round((float) ($umpStats->rerata_ump ?? 0)),
                    'rerata_formatted' => 'Rp ' . number_format($umpStats->rerata_ump ?? 0, 0, ',', '.'),
                    'nominal_tertinggi' => (float) ($umpStats->max_ump ?? 0),
                    'nominal_terendah' => (float) ($umpStats->min_ump ?? 0),
                ],
                'sensus_pekerja' => [
                    'total_sampel_laporan' => (int) ($workerStats->total_sampel ?? 0),
                    'rerata_upah_riil' => round((float) ($workerStats->rerata_gaji ?? 0)),
                    'rerata_formatted' => 'Rp ' . number_format($workerStats->rerata_gaji ?? 0, 0, ',', '.'),
                    'upah_tertinggi' => (float) ($workerStats->max_gaji ?? 0),
                    'upah_terendah' => (float) ($workerStats->min_gaji ?? 0),
                ],
            ],
        ]);
    }
}
