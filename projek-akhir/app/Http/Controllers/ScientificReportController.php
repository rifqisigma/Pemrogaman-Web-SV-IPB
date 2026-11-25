<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Ump;
use App\Models\WageLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScientificReportController extends Controller
{
    public function index(Request $request)
    {
        $availableYears = Ump::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $selectedYear = (int) $request->input('tahun', $availableYears->first() ?? 2026);

        // Core dataset for this year
        $umps = Ump::with('provinsi')
            ->where('tahun', $selectedYear)
            ->get()
            ->sortByDesc('nominal');

        $count = $umps->count();
        $avgUmp = $count > 0 ? $umps->avg('nominal') : 0;
        $highest = $umps->first();
        $lowest = $umps->last();

        // Calculate standard deviation of UMP to give scientific statistical depth
        $variance = 0;
        if ($count > 1) {
            foreach ($umps as $u) {
                $variance += pow($u->nominal - $avgUmp, 2);
            }
            $stdDev = sqrt($variance / ($count - 1));
            $coeffVariation = round(($stdDev / $avgUmp) * 100, 2);
        } else {
            $stdDev = 0;
            $coeffVariation = 0;
        }

        // Sektor aggregates
        $sectorBreakdown = WageLog::select('bidang_kerja', DB::raw('AVG(gaji_nominal) as rerata_upah'), DB::raw('COUNT(*) as n_sampel'))
            ->groupBy('bidang_kerja')
            ->orderBy('rerata_upah', 'desc')
            ->get();

        return view('scientific-report.index', compact(
            'availableYears',
            'selectedYear',
            'umps',
            'count',
            'avgUmp',
            'highest',
            'lowest',
            'stdDev',
            'coeffVariation',
            'sectorBreakdown'
        ));
    }

    public function downloadPdf(Request $request)
    {
        // Enforce role filtration: guests cannot download
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('warning', 'Akses Terbatas: Silakan masuk atau daftar terlebih dahulu untuk mengunduh dokumen Laporan Ilmiah resmi (PDF).');
        }

        $availableYears = Ump::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $year = (int) $request->input('tahun', $availableYears->first() ?? 2026);

        $umps = Ump::with('provinsi')
            ->where('tahun', $year)
            ->get()
            ->sortByDesc('nominal');

        $count = $umps->count();
        $avgUmp = $count > 0 ? $umps->avg('nominal') : 0;
        $highest = $umps->first();
        $lowest = $umps->last();

        $variance = 0;
        if ($count > 1) {
            foreach ($umps as $u) {
                $variance += pow($u->nominal - $avgUmp, 2);
            }
            $stdDev = sqrt($variance / ($count - 1));
            $coeffVariation = round(($stdDev / $avgUmp) * 100, 2);
        } else {
            $stdDev = 0;
            $coeffVariation = 0;
        }

        $sectorBreakdown = WageLog::select('bidang_kerja', DB::raw('AVG(gaji_nominal) as rerata_upah'), DB::raw('COUNT(*) as n_sampel'))
            ->groupBy('bidang_kerja')
            ->orderBy('rerata_upah', 'desc')
            ->get();

        $generatedDate = now()->translatedFormat('d F Y, H:i');
        $userDownloaded = Auth::user()->name;

        $pdf = Pdf::loadView('scientific-report.pdf', compact(
            'year',
            'umps',
            'count',
            'avgUmp',
            'highest',
            'lowest',
            'stdDev',
            'coeffVariation',
            'sectorBreakdown',
            'generatedDate',
            'userDownloaded'
        ))->setPaper('a4', 'portrait');

        $filename = "Laporan_Ilmiah_Upah_Nasional_{$year}.pdf";

        return $pdf->download($filename);
    }
}
