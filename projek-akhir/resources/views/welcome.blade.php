@extends('layouts.app')

@section('title', 'Peta & Dashboard Upah Nasional')

@section('content')
<div class="space-y-8">

    <section class="card-tactile p-6 sm:p-8 bg-gradient-to-br from-white via-emerald-50/20 to-slate-50 relative overflow-hidden">
        <div class="max-w-3xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold border border-emerald-300 mb-3">
                <i class="fa-solid fa-satellite-dish text-emerald-600 animate-pulse"></i>
                <span>Laporan Observasi Upah Kerja Nasional {{ $year }}</span>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight">
                Peta Kondisi Upah & <span class="text-emerald-600 underline decoration-emerald-300 decoration-wavy">UMP Indonesia</span>
            </h1>
            <p class="text-sm sm:text-base text-slate-600 mt-3 leading-relaxed">
                Eksplorasi komprehensif ketenagakerjaan Indonesia. Pantau disparitas Upah Minimum Provinsi (UMP), benchmark upah riil lintas sektor industri, dan tren komparatif 38 provinsi secara interaktif.
            </p>
        </div>

        <div class="mt-6 pt-5 border-t-2 border-slate-200/80 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Pilih Periode:</span>
                <form action="{{ route('home') }}" method="GET" id="yearForm" class="flex items-center gap-2">
                    <select name="tahun" id="tahunSelect" onchange="this.form.submit()" 
                            class="px-4 py-2 bg-white border-2 border-slate-300 rounded-xl text-sm font-bold text-slate-800 shadow-sm focus:border-emerald-500 focus:outline-none">
                        @foreach($years as $yr)
                            <option value="{{ $yr }}" {{ $yr == $year ? 'selected' : '' }}>
                                Tahun {{ $yr }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('scientific-report.index') }}" class="btn-tactile-secondary px-3.5 py-2 text-xs inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-file-pdf text-rose-500"></i> Laporan Ilmiah
                </a>
                <a href="{{ route('datasheet.index') }}" class="btn-tactile-secondary px-3.5 py-2 text-xs inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-file-excel text-emerald-600"></i> Unduh Dataset
                </a>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="card-tactile p-5 bg-white flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Rata-Rata UMP Nasional</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-calculator"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900 tracking-tight">
                    Rp {{ number_format($avg, 0, ',', '.') }}
                </div>
                <div class="flex items-center gap-2 mt-2">
                    <span class="badge-pill {{ $growth >= 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                        <i class="fa-solid {{ $growth >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} mr-1"></i>
                        {{ $growth >= 0 ? '+' : '' }}{{ $growth }}% YoY
                    </span>
                    <span class="text-[11px] text-slate-400 font-medium">vs {{ $year - 1 }}</span>
                </div>
            </div>
        </div>

        <div class="card-tactile p-5 bg-white flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">UMP Tertinggi</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-crown text-amber-500"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-black text-emerald-600 tracking-tight">
                    {{ $highest ? 'Rp ' . number_format($highest['nominal'], 0, ',', '.') : '-' }}
                </div>
                <div class="mt-2 text-xs font-bold text-slate-700 truncate">
                    <i class="fa-solid fa-location-dot text-emerald-500 mr-1"></i>
                    {{ $highest['nama'] ?? '-' }}
                </div>
            </div>
        </div>

        <div class="card-tactile p-5 bg-white flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">UMP Terendah</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-arrow-down-wide-short"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-700 tracking-tight">
                    {{ $lowest ? 'Rp ' . number_format($lowest['nominal'], 0, ',', '.') : '-' }}
                </div>
                <div class="mt-2 text-xs font-bold text-slate-700 truncate">
                    <i class="fa-solid fa-location-dot text-slate-400 mr-1"></i>
                    {{ $lowest['nama'] ?? '-' }}
                </div>
            </div>
        </div>

        <div class="card-tactile p-5 bg-white flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Rerata Upah Riil Pekerja</span>
                <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-users-viewfinder"></i>
                </div>
            </div>
            <div>
                <div class="text-2xl font-black text-sky-600 tracking-tight">
                    {{ $workersAvg > 0 ? 'Rp ' . number_format($workersAvg, 0, ',', '.') : '-' }}
                </div>
                <div class="mt-2 text-[11px] text-slate-500 font-medium flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Berdasarkan {{ $workersCount }} laporan pekerja aktif</span>
                </div>
            </div>
        </div>

    </section>

    <section class="card-tactile p-6 bg-white space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <i class="fa-solid fa-map-location-dot text-emerald-600"></i>
                        Peta Sebaran Upah Minimum Provinsi (UMP) {{ $year }}
                    </h2>
                    <span class="badge-pill bg-emerald-100 text-emerald-800 text-[10px]">Interaktif</span>
                </div>
                <p class="text-xs text-slate-500 mt-1">
                    Arahkan kursor atau klik pada lingkaran provinsi untuk melihat rincian UMP dan rerata upah pekerja daerah tersebut.
                </p>
            </div>

            <div class="flex items-center gap-3 text-xs bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-xl">
                <span class="font-bold text-slate-500">Tingkat UMP:</span>
                <div class="flex items-center gap-1">
                    <span class="w-3 h-3 rounded-full bg-emerald-600 inline-block"></span>
                    <span class="text-slate-600">&gt; Rp 4.5 Juta</span>
                </div>
                <div class="flex items-center gap-1">
                    <span class="w-3 h-3 rounded-full bg-sky-500 inline-block"></span>
                    <span class="text-slate-600">Rp 3 - 4.5 Juta</span>
                </div>
                <div class="flex items-center gap-1">
                    <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                    <span class="text-slate-600">&lt; Rp 3 Juta</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            
            <div class="lg:col-span-2 rounded-2xl overflow-hidden border-2 border-slate-200 relative" style="height: 480px; z-index: 1;">
                <div id="indonesiaMap" class="w-full h-full bg-slate-100"></div>
            </div>

            <div id="provinceDetailCard" class="card-tactile p-5 bg-slate-50 border-2 border-slate-200 flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="badge-pill bg-slate-200 text-slate-700 text-[10px]">Detail Regional</span>
                        <span id="detailKode" class="font-mono text-xs text-slate-400">PILIH PROVINSI</span>
                    </div>
                    <h3 id="detailNama" class="text-xl font-black text-slate-800">Klik Salah Satu Provinsi</h3>
                    <p id="detailSubtitle" class="text-xs text-slate-500 mt-1">
                        Pilih titik lingkaran di peta Indonesia untuk memuat analisis statistik lengkap provinsi bersangkutan.
                    </p>

                    <div class="mt-5 space-y-3 pt-3 border-t-2 border-slate-200">
                        <div class="bg-white p-3 rounded-xl border border-slate-200">
                            <span class="text-[11px] font-bold uppercase text-slate-400">Nominal UMP (Tahun {{ $year }})</span>
                            <div id="detailUmp" class="text-xl font-black text-emerald-600 mt-0.5">-</div>
                        </div>

                        <div class="bg-white p-3 rounded-xl border border-slate-200">
                            <span class="text-[11px] font-bold uppercase text-slate-400">Pertumbuhan Tahunan (YoY)</span>
                            <div id="detailGrowth" class="text-sm font-bold text-slate-700 mt-0.5">-</div>
                        </div>

                        <div class="bg-white p-3 rounded-xl border border-slate-200">
                            <span class="text-[11px] font-bold uppercase text-slate-400">Rerata Upah Riil Kontribusi Pekerja</span>
                            <div id="detailReported" class="text-sm font-bold text-sky-700 mt-0.5">-</div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-slate-200">
                    <a href="{{ route('datasheet.index') }}" class="btn-tactile-secondary w-full py-2.5 text-xs flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        <span>Buka di Datasheet Lengkap</span>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="card-tactile p-6 bg-white space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-black text-slate-900 text-base flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-emerald-600"></i>
                        Tren Rerata UMP Nasional (8 Tahun Terakhir)
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Dinamika kenaikan upah rata-rata seluruh provinsi</p>
                </div>
                <span class="badge-pill bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px]">Tren Makro</span>
            </div>
            <div class="h-64">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <div class="card-tactile p-6 bg-white space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="font-black text-slate-900 text-base flex items-center gap-2">
                        <i class="fa-solid fa-chart-bar text-sky-600"></i>
                        Disparitas 5 Provinsi Tertinggi vs Terendah
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Memperlihatkan ketimpangan geografis standar upah minimum</p>
                </div>
                <span class="badge-pill bg-sky-50 text-sky-700 border border-sky-200 text-[10px]">Disparitas</span>
            </div>
            <div class="h-64">
                <canvas id="rankingChart"></canvas>
            </div>
        </div>

    </section>

    @if($sectors->isNotEmpty())
    <section class="card-tactile p-6 bg-white space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-black text-slate-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-layer-group text-emerald-600"></i>
                    Benchmark Upah Riil Pekerja per Sektor Industri
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">
                    Data dikumpulkan secara langsung dari formulir profil tenaga kerja terverifikasi di National Wage.
                </p>
            </div>
            <a href="{{ route('user.index') }}" class="btn-tactile-primary px-4 py-2 text-xs inline-flex items-center gap-1.5 self-start sm:self-auto">
                <i class="fa-solid fa-plus-circle"></i>
                <span>Kontribusi Data Upah Anda</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 pt-2">
            @foreach($sectors as $sec)
                <div class="bg-slate-50 border-2 border-slate-200/80 rounded-2xl p-4 flex flex-col justify-between hover:border-emerald-300 transition-all">
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="badge-pill bg-slate-200 text-slate-700 text-[10px]">{{ $sec->total_pekerja }} Laporan</span>
                            <i class="fa-solid fa-briefcase text-slate-400"></i>
                        </div>
                        <h4 class="font-black text-slate-800 text-sm mt-1 leading-snug">{{ $sec->bidang_kerja }}</h4>
                    </div>
                    <div class="mt-4 pt-2 border-t border-slate-200">
                        <span class="text-[10px] uppercase font-bold text-slate-400">Rerata Upah Riil</span>
                        <div class="text-base font-extrabold text-emerald-700">
                            Rp {{ number_format($sec->avg_gaji, 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

    <section class="card-tactile p-6 bg-white space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-black text-slate-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-list-ol text-emerald-600"></i>
                    Daftar Lengkap Peringkat UMP Seluruh Provinsi ({{ $year }})
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Total {{ count($provinces) }} provinsi terdaftar dalam basis data resmi</p>
            </div>
        </div>

        <div class="overflow-x-auto rounded-2xl">
            <table class="w-full text-left text-xs border-collapse" id="provinsiTable">
                <thead class="bg-slate-100/80 border-b-2 border-slate-200 text-slate-600 font-extrabold uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 text-center w-12">#</th>
                        <th class="py-3 px-4">Nama Provinsi</th>
                        <th class="py-3 px-4 text-right">Besaran UMP</th>
                        <th class="py-3 px-4 text-right">Pertumbuhan (YoY)</th>
                        <th class="py-3 px-4 text-center">Status vs Rerata</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @foreach($provinces as $idx => $p)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                            <td class="py-3 px-4 font-bold text-slate-900">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-lg bg-slate-100 text-slate-600 inline-flex items-center justify-center text-[10px] font-mono font-bold">
                                        {{ $p['kode'] }}
                                    </span>
                                    <span>{{ $p['nama'] }}</span>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-right font-black text-emerald-700 text-sm" data-order="{{ $p['nominal'] }}">
                                {{ $p['formatted'] }}
                            </td>
                            <td class="py-3 px-4 text-right" data-order="{{ $p['growth'] ?? -999 }}">
                                @if(!is_null($p['growth']))
                                    <span class="inline-flex items-center gap-1 font-bold {{ $p['growth'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $p['growth'] >= 0 ? '+' : '' }}{{ $p['growth'] }}%
                                    </span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($p['nominal'] >= $avg)
                                    <span class="badge-pill bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px]">
                                        &ge; Rerata Nasional
                                    </span>
                                @else
                                    <span class="badge-pill bg-amber-50 text-amber-700 border border-amber-200 text-[10px]">
                                        &lt; Rerata Nasional
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <button type="button" onclick="focusProvince({{ $p['lat'] }}, {{ $p['lng'] }}, '{{ addslashes($p['nama']) }}', '{{ $p['formatted'] }}', '{{ $p['kode'] }}', '{{ $p['growth'] ?? '-' }}%', '{{ $p['reported_formatted'] }}')"
                                        class="px-2.5 py-1 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg text-[11px] font-bold text-slate-600 transition">
                                    <i class="fa-solid fa-crosshairs mr-1"></i> Lihat di Peta
                                </button>
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
    const provinces = @json($provinces);
    const trendData = @json($trend);
    const topData = @json($top);
    const bottomData = @json($bottom);
    const natAvg = {{ $avg }};

    const map = L.map('indonesiaMap', {
        center: [-2.548926, 118.0148634],
        zoom: 5,
        minZoom: 4,
        maxZoom: 9,
        scrollWheelZoom: false
    });

    L.tileLayer('https://basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}.png?key=cb1_5fts_1_3064525766c151a00b1b7434', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        maxZoom: 19
    }).addTo(map);

    let markers = [];

    function getColor(nominal) {
        if (nominal >= 4500000) return '#059669';
        if (nominal >= 3000000) return '#0284c7';
        return '#f59e0b';
    }

    provinces.forEach(p => {
        const color = getColor(p.nominal);
        const radius = Math.max(8, Math.min(22, (p.nominal / 300000)));

        const circle = L.circleMarker([p.lat, p.lng], {
            radius: radius,
            fillColor: color,
            color: '#ffffff',
            weight: 2,
            opacity: 1,
            fillOpacity: 0.85
        }).addTo(map);

        circle.bindTooltip(`
            <div style="font-family:'Plus Jakarta Sans',sans-serif; padding:4px;">
                <div style="font-weight:800; font-size:13px; color:#0f172a;">${p.nama}</div>
                <div style="font-weight:700; color:${color}; font-size:12px; margin-top:2px;">${p.formatted}</div>
                <div style="font-size:10px; color:#64748b; margin-top:1px;">Pertumbuhan: ${p.growth ? p.growth + '%' : '-'}</div>
            </div>
        `, { sticky: true });

        circle.on('click', () => {
            selectProvince(p);
        });

        markers.push({ id: p.id, marker: circle, data: p });
    });

    function selectProvince(p) {
        document.getElementById('detailKode').textContent = 'KODE: ' + p.kode;
        document.getElementById('detailNama').textContent = p.nama;
        document.getElementById('detailSubtitle').textContent = 'Informasi statistik dan benchmark upah provinsi ' + p.nama + '.';
        document.getElementById('detailUmp').textContent = p.formatted;
        document.getElementById('detailGrowth').textContent = (p.growth !== null ? (p.growth >= 0 ? '+' : '') + p.growth + '% YoY' : 'Data tahun lalu tidak tersedia');
        document.getElementById('detailReported').textContent = p.reported_formatted + (p.reported_count > 0 ? ` (${p.reported_count} sampel)` : '');
        
        map.setView([p.lat, p.lng], 6, { animate: true });
    }

    window.focusProvince = function(lat, lng, nama, formatted, kode, growth, reported) {
        document.getElementById('detailKode').textContent = 'KODE: ' + kode;
        document.getElementById('detailNama').textContent = nama;
        document.getElementById('detailSubtitle').textContent = 'Informasi statistik provinsi ' + nama + '.';
        document.getElementById('detailUmp').textContent = formatted;
        document.getElementById('detailGrowth').textContent = growth;
        document.getElementById('detailReported').textContent = reported;

        map.setView([lat, lng], 7, { animate: true });
        window.scrollTo({ top: document.getElementById('indonesiaMap').offsetTop - 100, behavior: 'smooth' });
    };

    if (provinces.length > 0) {
        selectProvince(provinces[0]);
    }

    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: trendData.map(d => d.tahun),
            datasets: [{
                label: 'Rerata UMP Nasional (Rp)',
                data: trendData.map(d => d.avg_nominal),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#059669',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + Number(context.raw).toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
                        },
                        font: { size: 10 }
                    },
                    grid: { color: '#f1f5f9' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11, weight: 'bold' } }
                }
            }
        }
    });

    const combinedRanking = [...topData, ...bottomData];
    const ctxRank = document.getElementById('rankingChart').getContext('2d');
    new Chart(ctxRank, {
        type: 'bar',
        data: {
            labels: combinedRanking.map(d => d.nama.replace('Kepulauan', 'Kep.').replace('Daerah Khusus Ibukota', 'DKI')),
            datasets: [{
                label: 'Besaran UMP (Rp)',
                data: combinedRanking.map(d => d.nominal),
                backgroundColor: combinedRanking.map((d, i) => i < 5 ? '#059669' : '#f59e0b'),
                borderRadius: 8
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + Number(context.raw).toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
                        },
                        font: { size: 10 }
                    },
                    grid: { color: '#f1f5f9' }
                },
                y: {
                    ticks: { font: { size: 10, weight: '600' } },
                    grid: { display: false }
                }
            }
        }
    });

    $(document).ready(function () {
        const provinsiTable = $('#provinsiTable').DataTable({
            pageLength: 10,
            order: [[0, 'asc']],
            language: {
                search: 'Cari Provinsi:',
                searchPlaceholder: 'Ketik nama provinsi...',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ provinsi',
                infoEmpty: 'Tidak ada data provinsi',
                zeroRecords: 'Nama provinsi tidak ditemukan'
            },
            columnDefs: [
                { targets: 5, orderable: false, searchable: false }
            ]
        });

        $('#tableSearch').on('keyup change', function () {
            provinsiTable.search(this.value).draw();
        });
    });
</script>
@endpush
