@extends('layouts.app')

@section('title', 'Scientific Report & Citation Generator')

@section('content')
<div class="space-y-8">

    <section class="card-tactile p-6 sm:p-8 bg-white border-t-8 border-t-emerald-700 space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b-2 border-slate-100 pb-4">
            <div class="flex items-center gap-2">
                <span class="badge-pill bg-emerald-900 text-white font-mono text-[10px]">NW-REPORT/{{ $year }}/V.1</span>
                <span class="badge-pill bg-slate-100 text-slate-700 text-[10px] font-mono">DOI: 10.5281/zenodo.nw{{ $year }}.01</span>
                <span class="badge-pill bg-slate-100 text-slate-600 text-[10px]">Klasifikasi JEL: J31, J38, R23</span>
            </div>

            <form action="{{ route('scientific-report.index') }}" method="GET" class="flex items-center gap-2">
                <label for="tahunSelect" class="text-xs font-bold text-slate-500 uppercase">Edisi:</label>
                <select name="tahun" id="tahunSelect" onchange="this.form.submit()" 
                        class="px-3 py-1.5 bg-slate-50 border-2 border-slate-300 rounded-xl text-xs font-bold text-slate-800 focus:outline-none focus:border-emerald-600">
                    @foreach($years as $yr)
                        <option value="{{ $yr }}" {{ $yr == $year ? 'selected' : '' }}>Edisi Riset {{ $yr }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div>
            <h1 class="text-2xl sm:text-3xl font-serif font-black text-slate-900 tracking-tight leading-snug">
                Laporan Komprehensif Upah Minimum Provinsi (UMP) dan Dinamika Kompensasi Tenaga Kerja Indonesia
            </h1>
            <p class="text-xs font-medium text-slate-500 mt-2">
                Diterbitkan oleh: <span class="font-bold text-slate-700">National Wage Policy & Labor Research Institute (NWPLRI)</span> &bull; 
                Observasi: Data Resmi Kemenaker RI, BPS, dan Sensus Partisipatif Pekerja {{ $year }}
            </p>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-emerald-50/60 p-4 rounded-2xl border-2 border-emerald-200">
            <div>
                <h4 class="font-black text-sm text-emerald-950 flex items-center gap-1.5">
                    <i class="fa-solid fa-file-pdf text-rose-600"></i> Unduh Dokumen Lengkap (PDF Ilmiah)
                </h4>
                <p class="text-xs text-emerald-800 mt-0.5">
                    Memuat ringkasan eksekutif, tabel observasi 38 provinsi, standar deviasi, dan matriks disparitas regional.
                </p>
            </div>

            <div>
                @auth
                    <a href="{{ route('scientific-report.pdf', ['tahun' => $year]) }}" 
                       class="btn-tactile-primary px-5 py-2.5 text-xs inline-flex items-center gap-2 shadow-sm whitespace-nowrap">
                        <i class="fa-solid fa-download"></i>
                        <span>Unduh PDF Ilmiah ({{ $year }})</span>
                    </a>
                @else
                    <div class="flex items-center gap-2">
                        <a href="{{ route('login') }}" 
                           class="btn-tactile-primary px-4 py-2 text-xs inline-flex items-center gap-2 whitespace-nowrap"
                           title="Masuk untuk mengunduh laporan">
                            <i class="fa-solid fa-lock text-xs"></i>
                            <span>Masuk Akun untuk Mengunduh PDF</span>
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </section>

    <section class="card-tactile p-6 sm:p-8 bg-white space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <i class="fa-solid fa-quote-right text-emerald-600"></i>
                        Generator Sitasi Ilmiah (Citation Generator)
                    </h2>
                    <span class="badge-pill bg-emerald-100 text-emerald-800 text-[10px]">Multi-Format</span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Salin kutipan resmi laporan ini untuk keperluan jurnal penelitian, skripsi, tesis, dan naskah akademik Anda.
                </p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2 border-b-2 border-slate-100 pb-3">
            <button type="button" onclick="switchCitation('apa7')" id="tab-apa7" class="cite-tab px-3.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-600 text-white transition">
                APA 7th Edition
            </button>
            <button type="button" onclick="switchCitation('ieee')" id="tab-ieee" class="cite-tab px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                IEEE
            </button>
            <button type="button" onclick="switchCitation('harvard')" id="tab-harvard" class="cite-tab px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                Harvard
            </button>
            <button type="button" onclick="switchCitation('chicago')" id="tab-chicago" class="cite-tab px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                Chicago (17th)
            </button>
            <button type="button" onclick="switchCitation('bibtex')" id="tab-bibtex" class="cite-tab px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                BibTeX
            </button>
        </div>

        <div class="relative bg-slate-50 border-2 border-slate-200 rounded-2xl p-4 sm:p-5">
            <pre id="citationText" class="text-xs font-mono text-slate-800 whitespace-pre-wrap leading-relaxed font-semibold"></pre>

            <div class="mt-3 flex items-center justify-between pt-3 border-t border-slate-200 text-xs">
                <span class="text-slate-400 font-medium" id="formatLabel">Format: APA (American Psychological Association) 7th Edition</span>
                <button type="button" onclick="copyCitation()" id="copyBtn" class="btn-tactile-secondary px-3 py-1.5 text-xs inline-flex items-center gap-1.5">
                    <i class="fa-regular fa-copy text-xs"></i>
                    <span id="copyBtnLabel">Salin Sitasi</span>
                </button>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 card-tactile p-6 bg-white space-y-4">
            <h3 class="text-base font-black text-slate-900 border-b-2 border-slate-100 pb-2 flex items-center gap-2">
                <i class="fa-solid fa-scroll text-slate-600"></i> Abstrak Akademik (Executive Abstract)
            </h3>
            
            <p class="text-xs text-slate-700 leading-relaxed font-serif text-justify">
                <strong>Abstrak &mdash;</strong> Laporan ini menganalisis konfigurasi spasial dan disparitas Upah Minimum Provinsi (UMP) di 38 provinsi di Indonesia untuk tahun observasi {{ $year }}. Pendekatan metodologis memadukan telaah regulasi ketenagakerjaan makro dengan observasi empiris upah riil yang dikontribusikan oleh tenaga kerja aktif lintas sektor. Hasil analisis menunjukkan tingkat disparitas yang signifikan antara wilayah perkotaan metropolitan (puncak tertinggi di {{ $highest->provinsi->nama_provinsi ?? 'DKI Jakarta' }} sebesar Rp {{ number_format($highest->nominal ?? 0, 0, ',', '.') }}) dibandingkan kawasan berbasis manufaktur dan agraris (terendah di {{ $lowest->provinsi->nama_provinsi ?? '-' }} sebesar Rp {{ number_format($lowest->nominal ?? 0, 0, ',', '.') }}). Koefisien variasi sebesar {{ $cv }}% mengindikasikan persistensi ketimpangan upah antar-regional yang memerlukan kebijakan harmonisasi struktural produktivitas tenaga kerja.
            </p>

            <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-200 text-xs space-y-1">
                <div><strong class="text-slate-700">Kata Kunci:</strong> <span class="italic text-slate-600">Upah Minimum Provinsi, UMP {{ $year }}, Disparitas Regional, Produktivitas Tenaga Kerja, Kebijakan Upah Nasional, BPS.</span></div>
            </div>
        </div>

        <div class="card-tactile p-6 bg-white space-y-4">
            <h3 class="text-base font-black text-slate-900 border-b-2 border-slate-100 pb-2 flex items-center gap-2">
                <i class="fa-solid fa-square-root-variable text-emerald-600"></i> Statistik Deskriptif
            </h3>

            <div class="space-y-3">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-[10px] font-bold uppercase text-slate-400">Mean (&mu; - Rerata UMP)</span>
                    <div class="text-lg font-black text-slate-900 mt-0.5">Rp {{ number_format($avg, 0, ',', '.') }}</div>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-[10px] font-bold uppercase text-slate-400">Standar Deviasi (&sigma;)</span>
                    <div class="text-lg font-black text-slate-900 mt-0.5">&plusmn; Rp {{ number_format($stdDev, 0, ',', '.') }}</div>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="text-[10px] font-bold uppercase text-slate-400">Koefisien Variasi (CV)</span>
                    <div class="text-lg font-black text-emerald-700 mt-0.5">{{ $cv }}%</div>
                    <p class="text-[10px] text-slate-400 mt-0.5">Indeks dispersi ketimpangan antar provinsi</p>
                </div>
            </div>
        </div>

    </section>

    <section class="card-tactile p-6 bg-white space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-black text-slate-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-table text-emerald-600"></i>
                    Matriks Data Observasi UMP Seluruh Provinsi (Tahun {{ $year }})
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar lengkap urutan nominal dan penyimpangan dari nilai mean nasional</p>
            </div>
            <span class="badge-pill bg-slate-100 text-slate-700 text-xs">N = {{ $count }} Provinsi</span>
        </div>

        <div class="overflow-x-auto rounded-2xl border-2 border-slate-200">
            <table class="w-full text-left text-xs" data-datatable>
                <thead class="bg-slate-100/90 border-b-2 border-slate-200 text-slate-600 font-extrabold uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Kode</th>
                        <th class="py-3 px-4">Wilayah Provinsi</th>
                        <th class="py-3 px-4 text-right">Nominal UMP (IDR)</th>
                        <th class="py-3 px-4 text-right">Deviasi vs Mean (&Delta;)</th>
                        <th class="py-3 px-4 text-center">Kategori</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @foreach($umps as $idx => $ump)
                        @php
                            $delta = $ump->nominal - $avg;
                            $pctDelta = $avg > 0 ? round(($delta / $avg) * 100, 1) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-2.5 px-4 text-center font-bold text-slate-400">{{ $loop->iteration }}</td>
                            <td class="py-2.5 px-4 font-mono font-bold text-slate-600">{{ $ump->provinsi->kode_provinsi ?? '-' }}</td>
                            <td class="py-2.5 px-4 font-extrabold text-slate-900">{{ $ump->provinsi->nama_provinsi ?? '-' }}</td>
                            <td class="py-2.5 px-4 text-right font-black text-emerald-700 font-mono">
                                Rp {{ number_format($ump->nominal, 0, ',', '.') }}
                            </td>
                            <td class="py-2.5 px-4 text-right font-mono {{ $delta >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $delta >= 0 ? '+' : '' }}Rp {{ number_format($delta, 0, ',', '.') }} ({{ $pctDelta }}%)
                            </td>
                            <td class="py-2.5 px-4 text-center">
                                @if($ump->nominal > $avg + ($stdDev * 0.5))
                                    <span class="badge-pill bg-emerald-100 text-emerald-800 text-[10px]">Tinggi</span>
                                @elseif($ump->nominal < $avg - ($stdDev * 0.5))
                                    <span class="badge-pill bg-amber-100 text-amber-800 text-[10px]">Rendah</span>
                                @else
                                    <span class="badge-pill bg-slate-100 text-slate-700 text-[10px]">Moderat</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

</div>

@push('scripts')
<script>
    const year = {{ $year }};

    const citations = {
        apa7: `National Wage Institute. (${year}). Laporan Komprehensif Upah Minimum Provinsi (UMP) dan Dinamika Kompensasi Tenaga Kerja Indonesia. National Wage Policy & Labor Research Institute. https://doi.org/10.5281/zenodo.nw${year}.01`,
        ieee: `National Wage Institute, "Laporan Komprehensif Upah Minimum Provinsi (UMP) dan Dinamika Kompensasi Tenaga Kerja Indonesia," NWPLRI Research Technical Report NW-${year}-01, ${year}. doi: 10.5281/zenodo.nw${year}.01.`,
        harvard: `National Wage Institute (${year}) 'Laporan Komprehensif Upah Minimum Provinsi (UMP) dan Dinamika Kompensasi Tenaga Kerja Indonesia', NWPLRI Annual Labor Series, Jakarta. Available at: https://doi.org/10.5281/zenodo.nw${year}.01.`,
        chicago: `National Wage Institute. ${year}. "Laporan Komprehensif Upah Minimum Provinsi (UMP) dan Dinamika Kompensasi Tenaga Kerja Indonesia." NWPLRI Policy Series. Jakarta: NWPLRI Press. https://doi.org/10.5281/zenodo.nw${year}.01.`,
        bibtex: `@` + `techreport{nationalwage${year},
  author      = "{National Wage Institute}",
  title       = {Laporan Komprehensif Upah Minimum Provinsi (UMP) dan Dinamika Kompensasi Tenaga Kerja Indonesia},
  institution = {National Wage Policy & Labor Research Institute},
  year        = {${year}},
  number      = {NW-${year}-01},
  address     = {Jakarta, Indonesia},
  doi         = {10.5281/zenodo.nw${year}.01}
}`
    };

    const formatLabels = {
        apa7: "Format: APA (American Psychological Association) 7th Edition",
        ieee: "Format: IEEE (Institute of Electrical and Electronics Engineers)",
        harvard: "Format: Harvard Referencing Standard",
        chicago: "Format: Chicago Manual of Style (17th Edition)",
        bibtex: "Format: BibTeX LaTeX Entry"
    };

    let currentFormat = 'apa7';

    function switchCitation(fmt) {
        currentFormat = fmt;
        document.getElementById('citationText').textContent = citations[fmt];
        document.getElementById('formatLabel').textContent = formatLabels[fmt];

        document.querySelectorAll('.cite-tab').forEach(b => {
            b.className = "cite-tab px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition";
        });
        document.getElementById('tab-' + fmt).className = "cite-tab px-3.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-600 text-white transition";
    }

    function copyCitation() {
        const text = document.getElementById('citationText').textContent;
        navigator.clipboard.writeText(text).then(() => {
            const label = document.getElementById('copyBtnLabel');
            label.textContent = "Berhasil Disalin!";
            setTimeout(() => {
                label.textContent = "Salin Sitasi";
            }, 2000);
        });
    }

    switchCitation('apa7');
</script>
@endpush
@endsection
