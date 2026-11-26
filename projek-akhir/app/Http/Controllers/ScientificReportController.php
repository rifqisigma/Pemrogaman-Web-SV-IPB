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
        $years = Ump::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $year = (int) $request->input('tahun', $years->first() ?? 2026);

        $umps = Ump::with('provinsi')
            ->where('tahun', $year)
            ->get()
            ->sortByDesc('nominal');

        $count = $umps->count();
        $avg = $count > 0 ? $umps->avg('nominal') : 0;
        $highest = $umps->first();
        $lowest = $umps->last();

        $variance = 0;
        if ($count > 1) {
            foreach ($umps as $u) {
                $variance += pow($u->nominal - $avg, 2);
            }
            $stdDev = sqrt($variance / ($count - 1));
            $cv = round(($stdDev / $avg) * 100, 2);
        } else {
            $stdDev = 0;
            $cv = 0;
        }

        $sectors = WageLog::select('bidang_kerja', DB::raw('AVG(gaji_nominal) as rerata_upah'), DB::raw('COUNT(*) as n_sampel'))
            ->groupBy('bidang_kerja')
            ->orderBy('rerata_upah', 'desc')
            ->get();

        return view('scientific-report.index', compact(
            'years',
            'year',
            'umps',
            'count',
            'avg',
            'highest',
            'lowest',
            'stdDev',
            'cv',
            'sectors'
        ));
    }

    public function downloadPdf(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('warning', 'Akses Terbatas: Silakan masuk atau daftar terlebih dahulu untuk mengunduh dokumen Laporan Ilmiah resmi (PDF).');
        }

        $years = Ump::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $year = (int) $request->input('tahun', $years->first() ?? 2026);

        $umps = Ump::with('provinsi')
            ->where('tahun', $year)
            ->get()
            ->sortByDesc('nominal');

        $count = $umps->count();
        $avg = $count > 0 ? $umps->avg('nominal') : 0;
        $highest = $umps->first();
        $lowest = $umps->last();

        $variance = 0;
        if ($count > 1) {
            foreach ($umps as $u) {
                $variance += pow($u->nominal - $avg, 2);
            }
            $stdDev = sqrt($variance / ($count - 1));
            $cv = round(($stdDev / $avg) * 100, 2);
        } else {
            $stdDev = 0;
            $cv = 0;
        }

        $sectors = WageLog::select('bidang_kerja', DB::raw('AVG(gaji_nominal) as rerata_upah'), DB::raw('COUNT(*) as n_sampel'))
            ->groupBy('bidang_kerja')
            ->orderBy('rerata_upah', 'desc')
            ->get();

        $date = now()->translatedFormat('d F Y, H:i');
        $user = Auth::user()->name;

        $pdf = Pdf::loadView('scientific-report.pdf', compact(
            'year',
            'umps',
            'count',
            'avg',
            'highest',
            'lowest',
            'stdDev',
            'cv',
            'sectors',
            'date',
            'user'
        ))->setPaper('a4', 'portrait');

        $filename = "Laporan_Ilmiah_Upah_Nasional_{$year}.pdf";

        return $pdf->download($filename);
    }
}
