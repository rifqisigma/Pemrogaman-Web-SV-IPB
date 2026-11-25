@extends('layouts.app')

@section('title', 'Import & Export Spreadsheet UMP')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.index') }}" class="text-xs font-bold text-slate-500 hover:text-emerald-600 transition flex items-center gap-1">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Panel Admin
                </a>
            </div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight mt-1">Import &amp; Export Spreadsheet UMP</h1>
            <p class="text-xs text-slate-500">Kelola dan sinkronisasi basis data Upah Minimum Provinsi melalui file Microsoft Excel atau CSV.</p>
        </div>
        <span class="badge-pill bg-emerald-100 text-emerald-800 text-xs">Spreadsheet Engine</span>
    </div>

    <!-- Kotak Export -->
    <div class="card-tactile p-6 bg-white space-y-3">
        <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-file-arrow-down text-emerald-600"></i> 1. Ekspor Data UMP Keseluruhan
        </h2>
        <p class="text-xs text-slate-600">
            Unduh seluruh database UMP yang tersimpan ke dalam format spreadsheet terstruktur:
        </p>
        <div class="flex items-center gap-2 pt-1">
            <a href="{{ route('admin.ump.spreadsheet.export', ['format' => 'xlsx']) }}" class="btn-tactile-primary px-4 py-2 text-xs inline-flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-file-excel"></i>
                <span>Download Excel (.xlsx)</span>
            </a>
            <a href="{{ route('admin.ump.spreadsheet.export', ['format' => 'csv']) }}" class="btn-tactile-secondary px-4 py-2 text-xs inline-flex items-center gap-1.5">
                <i class="fa-solid fa-file-csv"></i>
                <span>Download CSV (.csv)</span>
            </a>
        </div>
    </div>

    <!-- Kotak Import -->
    <div class="card-tactile p-6 bg-white space-y-4">
        <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-file-arrow-up text-sky-600"></i> 2. Impor Data UMP dari File
        </h2>
        <p class="text-xs text-slate-600">
            Unggah file Excel (<code>.xlsx</code>, <code>.xls</code>) atau CSV (<code>.csv</code>) untuk memperbarui atau menambahkan baris data UMP baru secara massal.
        </p>

        <form action="{{ route('admin.ump.spreadsheet.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4 bg-slate-50 p-4 rounded-2xl border-2 border-slate-200">
            @csrf
            <div>
                <label for="file" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Pilih File Berkas:</label>
                <input type="file" id="file" name="file" accept=".xlsx,.xls,.csv" required
                       class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
            </div>
            <div>
                <button type="submit" class="btn-tactile-primary px-5 py-2.5 text-xs inline-flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span>Proses Impor File</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Kotak Format Template -->
    <div class="card-tactile p-6 bg-white space-y-3">
        <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-amber-600"></i> 3. Ketentuan Format Kolom Template
        </h2>
        <p class="text-xs text-slate-600">Pastikan baris pertama (header) file import memiliki nama kolom persis seperti berikut:</p>
        
        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100 text-slate-600 font-mono font-bold">
                    <tr>
                        <th class="py-2.5 px-3">kode_provinsi</th>
                        <th class="py-2.5 px-3">nama_provinsi</th>
                        <th class="py-2.5 px-3">tahun</th>
                        <th class="py-2.5 px-3">nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-mono text-slate-700">
                    <tr>
                        <td class="py-2 px-3">32</td>
                        <td class="py-2 px-3">Jawa Barat</td>
                        <td class="py-2 px-3">2026</td>
                        <td class="py-2 px-3">2191238</td>
                    </tr>
                    <tr>
                        <td class="py-2 px-3">31</td>
                        <td class="py-2 px-3">DKI Jakarta</td>
                        <td class="py-2 px-3">2026</td>
                        <td class="py-2 px-3">5396761</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
