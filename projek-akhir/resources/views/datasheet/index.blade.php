@extends('layouts.app')

@section('title', 'Datasheet & Generator Excel Upah')

@section('content')
<div class="space-y-8">
    <section class="card-tactile p-6 sm:p-8 bg-white border-t-8 border-t-emerald-600 space-y-3">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="badge-pill bg-emerald-100 text-emerald-800 text-xs font-bold">
                    <i class="fa-solid fa-file-excel mr-1 text-emerald-600"></i> Generator Dataset Terstruktur
                </span>
                <span id="header-total-badge" class="badge-pill bg-slate-100 text-slate-700 text-xs font-bold">
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

    <section class="card-tactile p-6 bg-white space-y-5">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-emerald-600"></i> Kustomisasi Filter Data
            </h2>
            <span class="text-xs text-slate-400 font-medium">Filter tabel & ekspor otomatis menyesuaikan pilihan</span>
        </div>

        <form id="filter-form" action="{{ route('datasheet.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label for="provinsi_id" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Cakupan Wilayah (Nasional / Provinsi)
                </label>
                <select name="provinsi_id" id="provinsi_id" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none">
                    <option value="">-- Semua Provinsi (Nasional) --</option>
                    @foreach($provinsis as $p)
                        <option value="{{ $p->id }}" data-nama="{{ $p->nama_provinsi }}" {{ $provinsiId == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_provinsi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tahun_dari" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Dari Tahun
                </label>
                <select name="tahun_dari" id="tahun_dari" 
                        class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none">
                    <option value="">-- Tahun Terawal --</option>
                    @foreach($years->sort() as $yr)
                        <option value="{{ $yr }}" {{ $tahunDari == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                    @endforeach
                </select>
            </div>

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
                    <button type="button" id="btn-reset-filter" class="px-3.5 py-2 text-xs font-bold text-slate-500 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition flex items-center gap-1.5">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>Reset Filter</span>
                    </button>
                </div>

                <!-- Export Buttons Group -->
                <div class="flex items-center gap-2">
                    @auth
                        <a id="btn-export-xlsx" href="{{ route('datasheet.export', array_merge(request()->query(), ['format' => 'xlsx'])) }}" class="btn-tactile-primary px-4 py-2 text-xs inline-flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-file-excel"></i>
                            <span>Unduh Excel (.xlsx)</span>
                        </a>
                        <a id="btn-export-csv" href="{{ route('datasheet.export', array_merge(request()->query(), ['format' => 'csv'])) }}" class="btn-tactile-secondary px-4 py-2 text-xs inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-file-csv"></i>
                            <span>Unduh CSV</span>
                        </a>
                    @else
                        <div class="flex items-center gap-2">
                            <span class="text-[11px] text-slate-500 font-semibold hidden sm:inline">Unduh file memerlukan akun:</span>
                            <a href="{{ route('login') }}" class="btn-tactile-primary px-4 py-2 text-xs inline-flex items-center gap-1.5 shadow-sm" title="Masuk untuk mengunduh dataset">
                                <i class="fa-solid fa-lock text-xs"></i>
                                <span>Masuk Akun untuk Unduh Excel</span>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

        </form>
    </section>

    <section class="card-tactile p-6 bg-white space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <div>
                <h3 class="font-black text-slate-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-table text-emerald-600"></i>
                    <span id="preview-info-text">
                        Pratinjau Data
                    </span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Tampilan langsung data yang akan diekspor ke dalam file lembar kerja.</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl">
            <table class="w-full text-left text-xs" id="datasheetTable">
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
                    @foreach($previewData as $row)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2.5 px-4 text-center font-bold text-slate-400" data-order="{{ $row->id }}">#{{ $row->id }}</td>
                            <td class="py-2.5 px-4 font-mono font-bold text-slate-600">{{ $row->provinsi->kode_provinsi ?? '-' }}</td>
                            <td class="py-2.5 px-4 font-extrabold text-slate-900">{{ $row->provinsi->nama_provinsi ?? '-' }}</td>
                            <td class="py-2.5 px-4 text-center font-bold text-slate-800">{{ $row->tahun }}</td>
                            <td class="py-2.5 px-4 text-right font-black text-emerald-700 font-mono text-sm" data-order="{{ $row->nominal }}">
                                Rp {{ number_format($row->nominal, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-4 text-center">
                                <span class="badge-pill bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px]">
                                    Terverifikasi
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Daftarkan filter range tahun dari helper global app.blade.php (kolom Tahun = indeks ke-3)
            if (window.AppDataTable && typeof window.AppDataTable.registerYearRangeFilter === 'function') {
                window.AppDataTable.registerYearRangeFilter('datasheetTable', '#tahun_dari', '#tahun_sampai', 3);
            }

            // 2. Inisialisasi DataTables (mewarisi bahasa & opsi default dari app.blade.php)
            const table = $('#datasheetTable').DataTable({
                pageLength: 20,
                order: [[3, 'desc'], [1, 'asc']],
                columnDefs: [
                    { targets: 5, orderable: false }
                ],
                language: {
                    info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data UMP',
                    zeroRecords: 'Data tidak ditemukan'
                },
                drawCallback: function () {
                    const info = this.api().page.info();
                    const currentShown = info.end - info.start;
                    const filteredTotal = info.recordsDisplay;
                    const currentPage = info.pages > 0 ? info.page + 1 : 0;
                    const totalPages = info.pages;

                    // Sinkronkan jumlah baris pratinjau, badge total, dan indikator halaman
                    $('#header-total-badge').text(`Total ${filteredTotal} Baris Data`);
                    $('#page-indicator-badge').text(`Hal. ${currentPage} dari ${totalPages}`);
                }
            });

            // 3. Sinkronkan URL tombol Unduh Excel & CSV dengan pilihan filter aktif
            function updateExportLinks() {
                const baseUrl = "{{ route('datasheet.export') }}";
                const params = new URLSearchParams();

                const provId = $('#provinsi_id').val();
                const tDari = $('#tahun_dari').val();
                const tSampai = $('#tahun_sampai').val();

                if (provId) params.set('provinsi_id', provId);
                if (tDari) params.set('tahun_dari', tDari);
                if (tSampai) params.set('tahun_sampai', tSampai);

                const xlsxBtn = document.getElementById('btn-export-xlsx');
                const csvBtn = document.getElementById('btn-export-csv');

                if (xlsxBtn) {
                    params.set('format', 'xlsx');
                    xlsxBtn.href = `${baseUrl}?${params.toString()}`;
                }
                if (csvBtn) {
                    params.set('format', 'csv');
                    csvBtn.href = `${baseUrl}?${params.toString()}`;
                }
            }

            // 4. Filter Provinsi secara langsung di DataTables
            $('#provinsi_id').on('change', function () {
                const selectedOption = $(this).find('option:selected');
                const namaProvinsi = $(this).val() ? selectedOption.data('nama') : '';

                if (namaProvinsi) {
                    const escaped = $.fn.dataTable.util.escapeRegex(namaProvinsi);
                    table.column(2).search('^' + escaped + '$', true, false).draw();
                } else {
                    table.column(2).search('').draw();
                }

                updateExportLinks();
            });

            // 5. Filter Range Tahun secara langsung di DataTables
            $('#tahun_dari, #tahun_sampai').on('change', function () {
                table.draw();
                updateExportLinks();
            });

            // 6. Tombol Reset Filter
            $('#btn-reset-filter').on('click', function () {
                $('#provinsi_id').val('');
                $('#tahun_dari').val('');
                $('#tahun_sampai').val('');

                table.search('').columns().search('').draw();
                updateExportLinks();
            });
        });
    </script>
@endpush