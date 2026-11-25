@extends('layouts.app')

@section('title', 'Datasheet & Generator Excel Upah')

@section('content')
<div class="space-y-8">

    <!-- Header Datasheet -->
    <section class="card-tactile p-6 sm:p-8 bg-white border-t-8 border-t-emerald-600 space-y-3">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="badge-pill bg-emerald-100 text-emerald-800 text-xs font-bold">
                    <i class="fa-solid fa-file-excel mr-1 text-emerald-600"></i> Generator Dataset Terstruktur
                </span>
                <span class="badge-pill bg-slate-100 text-slate-700 text-xs font-bold">
                    Total {{ $totalMatching }} Baris Data
                </span>
            </div>
        </div>

        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Datasheet Upah Kerja & UMP Seluruh Indonesia
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-3xl">
            Saring dan unduh berkas spreadsheet (Microsoft Excel / CSV) data upah resmi menurut lingkup nasional atau regional provinsi dengan rentang tahun yang dapat disesuaikan.
        </p>
    </section>

    <!-- FORM FILTER DATASET & EXPORT BUTTONS -->
    <section class="card-tactile p-6 bg-white space-y-5">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-emerald-600"></i> Kustomisasi Filter Data
            </h2>
            <span class="text-xs text-slate-400 font-medium">Pilih parameter sebelum mengekspor</span>
        </div>

        <form action="{{ route('datasheet.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            
            <!-- Filter Provinsi / Regional -->
            <div>
                <label for="provinsi_id" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Cakupan Wilayah (Nasional / Provinsi)
                </label>
                <select name="provinsi_id" id="provinsi_id" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none">
                    <option value="">-- Semua Provinsi (Nasional) --</option>
                    @foreach($provinsis as $p)
                        <option value="{{ $p->id }}" {{ $provinsiId == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_provinsi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Rentang Tahun Dari -->
            <div>
                <label for="tahun_dari" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Dari Tahun
                </label>
                <select name="tahun_dari" id="tahun_dari" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none">
                    <option value="">-- Tahun Terawal --</option>
                    @foreach($years->sortBy('asc') as $yr)
                        <option value="{{ $yr }}" {{ $tahunDari == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Rentang Tahun Sampai -->
            <div>
                <label for="tahun_sampai" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Sampai Tahun
                </label>
                <select name="tahun_sampai" id="tahun_sampai" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none">
                    <option value="">-- Tahun Terkini --</option>
                    @foreach($years as $yr)
                        <option value="{{ $yr }}" {{ $tahunSampai == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3 flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-100">
                <div class="flex items-center gap-2">
                    <button type="submit" class="btn-tactile-secondary px-5 py-2 text-xs flex items-center gap-1.5">
                        <i class="fa-solid fa-filter"></i>
                        <span>Terapkan Filter Tabel</span>
                    </button>
                    @if($provinsiId || $tahunDari || $tahunSampai)
                        <a href="{{ route('datasheet.index') }}" class="px-3 py-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                            Reset Filter
                        </a>
                    @endif
                </div>

                <!-- Export Buttons Group -->
                <div class="flex items-center gap-2">
                    @auth
                        <a href="{{ route('datasheet.export', array_merge(request()->query(), ['format' => 'xlsx'])) }}" 
                           class="btn-tactile-primary px-4 py-2 text-xs inline-flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-file-excel"></i>
                            <span>Unduh Excel (.xlsx)</span>
                        </a>
                        <a href="{{ route('datasheet.export', array_merge(request()->query(), ['format' => 'csv'])) }}" 
                           class="btn-tactile-secondary px-4 py-2 text-xs inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-file-csv"></i>
                            <span>Unduh CSV</span>
                        </a>
                    @else
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] text-slate-500 font-semibold hidden sm:inline">Unduh file memerlukan akun:</span>
                            <a href="{{ route('login') }}" 
                               class="btn-tactile-primary px-4 py-2 text-xs inline-flex items-center gap-1.5 shadow-sm"
                               title="Masuk untuk mengunduh dataset">
                                <i class="fa-solid fa-lock text-xs"></i>
                                <span>Masuk Akun untuk Unduh Excel</span>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

        </form>
    </section>

    <!-- TABEL PREVIEW DATASET -->
    <section class="card-tactile p-6 bg-white space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-black text-slate-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-table text-emerald-600"></i>
                    Pratinjau Data (Preview {{ $previewData->count() }} dari {{ $totalMatching }} baris)
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Tampilan langsung data yang akan diekspor ke dalam file lembar kerja.</p>
            </div>
            <span class="badge-pill bg-slate-100 text-slate-700 text-xs">Hal. {{ $previewData->currentPage() }} dari {{ $previewData->lastPage() }}</span>
        </div>

        <div class="overflow-x-auto rounded-2xl border-2 border-slate-200">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100/90 border-b-2 border-slate-200 text-slate-600 font-extrabold uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">ID</th>
                        <th class="py-3 px-4">Kode Provinsi</th>
                        <th class="py-3 px-4">Nama Provinsi</th>
                        <th class="py-3 px-4 text-center">Tahun</th>
                        <th class="py-3 px-4 text-right">Nominal UMP Resmi</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @forelse($previewData as $row)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2.5 px-4 text-center font-bold text-slate-400">#{{ $row->id }}</td>
                            <td class="py-2.5 px-4 font-mono font-bold text-slate-600">{{ $row->provinsi->kode_provinsi ?? '-' }}</td>
                            <td class="py-2.5 px-4 font-extrabold text-slate-900">{{ $row->provinsi->nama_provinsi ?? '-' }}</td>
                            <td class="py-2.5 px-4 text-center font-bold text-slate-800">{{ $row->tahun }}</td>
                            <td class="py-2.5 px-4 text-right font-black text-emerald-700 font-mono text-sm">
                                Rp {{ number_format($row->nominal, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-4 text-center">
                                <span class="badge-pill bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px]">
                                    Terverifikasi
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 font-semibold">
                                Tidak ada data UMP yang cocok dengan kriteria filter yang Anda pilih.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pt-2">
            {{ $previewData->links() }}
        </div>
    </section>

</div>
@endsection
