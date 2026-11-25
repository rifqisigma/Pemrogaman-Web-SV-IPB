<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UmpExport;
use App\Http\Controllers\Controller;
use App\Imports\UmpImport;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class UmpSpreadsheetController extends Controller
{
    public function index()
    {
        return view('admin.ump-spreadsheet');
    }

    public function export(Request $request)
    {
        $format = strtolower((string) $request->query('format', 'xlsx'));

        if (!in_array($format, ['xlsx', 'csv'], true)) {
            $format = 'xlsx';
        }

        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "data-ump_{$timestamp}.{$format}";

        $writerType = $format === 'csv'
            ? \Maatwebsite\Excel\Excel::CSV
            : \Maatwebsite\Excel\Excel::XLSX;

        return Excel::download(new UmpExport, $filename, $writerType);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ], [
            'file.required' => 'Silakan pilih file Excel (.xlsx, .xls) atau CSV terlebih dahulu.',
            'file.file' => 'Input harus berupa file yang valid.',
            'file.mimes' => 'Format file harus .xlsx, .xls, atau .csv.',
            'file.max' => 'Ukuran file maksimal 5 MB.',
        ]);

        try {
            Excel::import(new UmpImport, $request->file('file'));

            return redirect()
                ->route('admin.ump.spreadsheet')
                ->with('success', 'Data UMP berhasil diimport ke database.');
        } catch (ValidationException $e) {
            return redirect()
                ->route('admin.ump.spreadsheet')
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.ump.spreadsheet')
                ->withErrors(['file' => 'Gagal memproses file: ' . $e->getMessage()])
                ->withInput();
        }
    }
}