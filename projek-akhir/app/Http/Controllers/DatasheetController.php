<?php

namespace App\Http\Controllers;

use App\Exports\CustomDatasheetExport;
use App\Models\Provinsi;
use App\Models\Ump;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class DatasheetController extends Controller
{
    public function index(Request $request)
    {
        $provinsis = Provinsi::orderBy('nama_provinsi')->get();
        $years = Ump::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        $provinsiId = $request->filled('provinsi_id') ? (int) $request->provinsi_id : null;
        $tahunDari = $request->filled('tahun_dari') ? (int) $request->tahun_dari : null;
        $tahunSampai = $request->filled('tahun_sampai') ? (int) $request->tahun_sampai : null;

        $query = Ump::with('provinsi');

        if ($provinsiId) {
            $query->where('provinsi_id', $provinsiId);
        }
        if ($tahunDari) {
            $query->where('tahun', '>=', $tahunDari);
        }
        if ($tahunSampai) {
            $query->where('tahun', '<=', $tahunSampai);
        }

        $previewData = $query->orderBy('tahun', 'desc')
            ->orderBy('provinsi_id', 'asc')
            ->get();

        $totalMatching = $previewData->count();

        return view('datasheet.index', compact(
            'provinsis',
            'years',
            'previewData',
            'totalMatching',
            'provinsiId',
            'tahunDari',
            'tahunSampai'
        ));
    }

    public function export(Request $request)
    {
        // Enforce role filtration: guests cannot download
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('warning', 'Akses Terbatas: Silakan masuk atau daftar terlebih dahulu untuk mengunduh file datasheet Excel/CSV.');
        }

        $format = strtolower((string) $request->input('format', 'xlsx'));
        if (!in_array($format, ['xlsx', 'csv'], true)) {
            $format = 'xlsx';
        }

        $provinsiId = $request->filled('provinsi_id') ? (int) $request->provinsi_id : null;
        $tahunDari = $request->filled('tahun_dari') ? (int) $request->tahun_dari : null;
        $tahunSampai = $request->filled('tahun_sampai') ? (int) $request->tahun_sampai : null;

        $provName = 'Nasional';
        if ($provinsiId) {
            $p = Provinsi::find($provinsiId);
            if ($p) {
                $provName = preg_replace('/[^a-zA-Z0-9]/', '_', $p->nama_provinsi);
            }
        }

        $timestamp = now()->format('Y-m-d_H-i');
        $filename = "datasheet_upah_{$provName}_{$timestamp}.{$format}";

        $writerType = $format === 'csv'
            ? \Maatwebsite\Excel\Excel::CSV
            : \Maatwebsite\Excel\Excel::XLSX;

        return Excel::download(
            new CustomDatasheetExport($provinsiId, $tahunDari, $tahunSampai),
            $filename,
            $writerType
        );
    }
}
