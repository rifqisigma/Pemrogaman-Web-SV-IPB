@extends('layouts.app')

@section('title', 'Dashboard Analisis UMP')

@section('content')
<div class="space-y-6">
    <!-- Header & Filter Tahun -->
    <div class="card-tactile p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span class="badge-pill bg-emerald-50 text-emerald-700 border border-emerald-200 mb-2">
                <i class="fa-solid fa-chart-line mr-1.5"></i> DASHBOARD ANALITIK
            </span>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Statistik & <span class="text-emerald-600">Komparasi UMP Nasional</span>
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Eksplorasi grafik perbandingan antar provinsi, tren kenaikan tahunan, serta pencarian data UMP secara interaktif.
            </p>
        </div>

        <div class="flex items-center gap-3 bg-slate-50 border-2 border-slate-200 rounded-2xl px-4 py-2.5 self-start md:self-auto">
            <label for="filter-tahun" class="text-xs font-extrabold uppercase tracking-wider text-slate-500">
                <i class="fa-regular fa-calendar mr-1 text-emerald-600"></i> Periode:
            </label>
            <select id="filter-tahun" class="bg-transparent text-sm font-extrabold text-slate-800 focus:outline-none cursor-pointer">
                <option value="2026" selected>Tahun 2026</option>
                <option value="2025">Tahun 2025</option>
                <option value="2024">Tahun 2024</option>
                <option value="2023">Tahun 2023</option>
                <option value="2022">Tahun 2022</option>
            </select>
        </div>
    </div>

    <!-- 4 Kartu Ringkasan Statistik (KPI) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Rata-rata UMP -->
        <div class="card-tactile p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Rata-rata UMP</span>
                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center">
                    <i class="fa-solid fa-calculator text-sm"></i>
                </div>
            </div>
            <h3 id="stat-rata-rata" class="text-2xl font-extrabold text-slate-900 mt-3">Memuat...</h3>
            <div class="mt-3 flex items-center gap-1.5 text-xs font-semibold text-emerald-700">
                <span class="badge-pill bg-emerald-50 text-emerald-700 border border-emerald-200">Rerata Nasional</span>
            </div>
        </div>

        <!-- UMP Tertinggi -->
        <div class="card-tactile p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">UMP Tertinggi</span>
                <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-500 border border-amber-200 flex items-center justify-center">
                    <i class="fa-solid fa-crown text-sm"></i>
                </div>
            </div>
            <h3 id="stat-tertinggi" class="text-2xl font-extrabold text-emerald-600 mt-3">Memuat...</h3>
            <p id="stat-provinsi-tertinggi" class="text-xs font-bold text-slate-600 mt-3 flex items-center gap-1">
                <i class="fa-solid fa-location-dot text-emerald-500"></i> <span>-</span>
            </p>
        </div>

        <!-- UMP Terendah -->
        <div class="card-tactile p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">UMP Terendah</span>
                <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-500 border border-rose-200 flex items-center justify-center">
                    <i class="fa-solid fa-arrow-trend-down text-sm"></i>
                </div>
            </div>
            <h3 id="stat-terendah" class="text-2xl font-extrabold text-rose-600 mt-3">Memuat...</h3>
            <p id="stat-provinsi-terendah" class="text-xs font-bold text-slate-600 mt-3 flex items-center gap-1">
                <i class="fa-solid fa-location-dot text-rose-500"></i> <span>-</span>
            </p>
        </div>

        <!-- Total Provinsi -->
        <div class="card-tactile p-5">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Cakupan Wilayah</span>
                <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-600 border border-sky-200 flex items-center justify-center">
                    <i class="fa-solid fa-map-location-dot text-sm"></i>
                </div>
            </div>
            <h3 id="stat-total-provinsi" class="text-2xl font-extrabold text-sky-600 mt-3">0 Provinsi</h3>
            <div class="mt-3">
                <span class="badge-pill bg-sky-50 text-sky-700 border border-sky-200">Data Terverifikasi</span>
            </div>
        </div>
    </div>

    <!-- Baris Grafik 1: Top 10 & Bottom 10 -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card-tactile p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Top 10 Provinsi UMP Tertinggi</h3>
                    <p class="text-xs text-slate-500">Peringkat 10 wilayah dengan standar upah minimum tertinggi</p>
                </div>
                <span class="badge-pill bg-emerald-50 text-emerald-700 border border-emerald-200">Top 10</span>
            </div>
            <div class="relative h-72">
                <canvas id="chartTop10"></canvas>
            </div>
        </div>

        <div class="card-tactile p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Bottom 10 Provinsi UMP Terendah</h3>
                    <p class="text-xs text-slate-500">Peringkat 10 wilayah dengan standar upah minimum terendah</p>
                </div>
                <span class="badge-pill bg-amber-50 text-amber-700 border border-amber-200">Bottom 10</span>
            </div>
            <div class="relative h-72">
                <canvas id="chartBottom10"></canvas>
            </div>
        </div>
    </div>

    <!-- Baris Grafik 2: Tren Nasional & Tren Spesifik Provinsi -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card-tactile p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Tren Rata-rata UMP Nasional</h3>
                    <p class="text-xs text-slate-500">Perkembangan rerata upah minimum dari tahun ke tahun</p>
                </div>
                <span class="badge-pill bg-sky-50 text-sky-700 border border-sky-200">Historis</span>
            </div>
            <div class="relative h-72">
                <canvas id="chartTrenNasional"></canvas>
            </div>
        </div>

        <div class="card-tactile p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                <div>
                    <h3 class="text-base font-extrabold text-slate-800">Tren UMP Per Provinsi</h3>
                    <p class="text-xs text-slate-500">Pilih provinsi untuk melihat riwayat pertumbuhannya</p>
                </div>
                <select id="filter-provinsi-tren" class="rounded-xl border-2 border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-700 focus:border-emerald-500 focus:outline-none">
                    <option value="">Memuat provinsi...</option>
                </select>
            </div>
            <div class="relative h-72">
                <canvas id="chartTrenProvinsi"></canvas>
            </div>
        </div>
    </div>

    <!-- Tabel Data Lengkap dengan DataTables -->
    <div class="card-tactile p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-5">
            <div>
                <h3 class="text-base font-extrabold text-slate-800">Daftar Lengkap UMP Seluruh Provinsi</h3>
                <p class="text-xs text-slate-500">Gunakan fitur pencarian, pengurutan kolom, dan paginasi interaktif DataTables</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table id="tabelUmpDataTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b-2 border-slate-200 text-xs font-extrabold uppercase tracking-wider text-slate-500">
                        <th class="py-3.5 px-4 w-16">No</th>
                        <th class="py-3.5 px-4">Provinsi</th>
                        <th class="py-3.5 px-4">Tahun</th>
                        <th class="py-3.5 px-4">Nominal UMP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm font-medium text-slate-700"></tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>    
        let chartTop10Instance = null;
        let chartBottom10Instance = null;
        let chartTrenNasionalInstance = null;
        let chartTrenProvinsiInstance = null;
        let umpDataTable = null;

        const formatRupiah = window.AppDataTable.formatRupiah;

        const getNamaProvinsi = (item) => {
            if (!item) return null;
            return item.provinsi?.nama_provinsi ||
                item.nama_provinsi ||
                (typeof item.provinsi === 'string' ? item.provinsi : null) ||
                null;
        };

        const getNilaiUmp = (item) => {
            if (!item) return 0;
            const val = item.nominal ?? item.rata_rata ?? item.rata_rata_ump ?? item.ump_nominal ?? item.ump ?? item.nilai ?? 0;
            return Number(val) || 0;
        };

        async function loadStatistik(tahun) {
            try {
                const res = await fetch(`/api/ump/statistik?tahun=${tahun}`);
                const json = await res.json();
                const data = json.data || json;

                document.getElementById('stat-rata-rata').textContent = formatRupiah(data.rata_rata);
                document.getElementById('stat-tertinggi').textContent = formatRupiah(data.tertinggi?.nominal ?? data.ump_tertinggi);

                const provTertinggi = getNamaProvinsi(data.tertinggi) || data.provinsi_tertinggi || '-';
                document.getElementById('stat-provinsi-tertinggi').querySelector('span').textContent = provTertinggi;

                document.getElementById('stat-terendah').textContent = formatRupiah(data.terendah?.nominal ?? data.ump_terendah);

                const provTerendah = getNamaProvinsi(data.terendah) || data.provinsi_terendah || '-';
                document.getElementById('stat-provinsi-terendah').querySelector('span').textContent = provTerendah;

                document.getElementById('stat-total-provinsi').textContent = `${data.total_provinsi ?? 38} Provinsi`;
            } catch (err) {
                console.error('Gagal memuat statistik:', err);
            }
        }

        // 2. Load Perbandingan Top 10 & Bottom 10 (/api/ump/perbandingan)
        async function loadPerbandingan(tahun) {
            try {
                const res = await fetch(`/api/ump/perbandingan?tahun=${tahun}`);
                const json = await res.json();
                const data = json.data || json;

                const top10 = data.top_10 || [];
                const bottom10 = data.bottom_10 || [];

                const spanTertinggi = document.getElementById('stat-provinsi-tertinggi').querySelector('span');
                if (top10.length > 0 && (spanTertinggi.textContent === '-' || !spanTertinggi.textContent.trim())) {
                    spanTertinggi.textContent = getNamaProvinsi(top10[0]) || '-';
                }

                const spanTerendah = document.getElementById('stat-provinsi-terendah').querySelector('span');
                if (bottom10.length > 0 && (spanTerendah.textContent === '-' || !spanTerendah.textContent.trim())) {
                    spanTerendah.textContent = getNamaProvinsi(bottom10[0]) || '-';
                }

                // Render Top 10
                const ctxTop = document.getElementById('chartTop10').getContext('2d');
                if (chartTop10Instance) chartTop10Instance.destroy();
                chartTop10Instance = new Chart(ctxTop, {
                    type: 'bar',
                    data: {
                        labels: top10.map(item => getNamaProvinsi(item)),
                        datasets: [{
                            label: 'UMP (Rp)',
                            data: top10.map(item => getNilaiUmp(item)),
                            backgroundColor: '#16a34a',
                            borderRadius: 8
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { ticks: { callback: val => 'Rp ' + (val / 1000000).toFixed(1) + ' Jt' } }
                        }
                    }
                });

                // Render Bottom 10
                const ctxBottom = document.getElementById('chartBottom10').getContext('2d');
                if (chartBottom10Instance) chartBottom10Instance.destroy();
                chartBottom10Instance = new Chart(ctxBottom, {
                    type: 'bar',
                    data: {
                        labels: bottom10.map(item => getNamaProvinsi(item)),
                        datasets: [{
                            label: 'UMP (Rp)',
                            data: bottom10.map(item => getNilaiUmp(item)),
                            backgroundColor: '#0284c7',
                            borderRadius: 8
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { ticks: { callback: val => 'Rp ' + (val / 1000000).toFixed(1) + ' Jt' } }
                        }
                    }
                });
            } catch (err) {
                console.error('Gagal memuat perbandingan:', err);
            }
        }

        // 3. Load Tren Nasional & Tren Provinsi (/api/ump/tren)
        async function loadTren(provinsiId = '') {
            try {
                const url = provinsiId ? `/api/ump/tren?provinsi_id=${provinsiId}` : `/api/ump/tren`;
                const res = await fetch(url);
                const json = await res.json();
                const data = json.data || json;

                if (!provinsiId) {
                    const listNasional = Array.isArray(data)
                        ? data
                        : (data.tren_nasional || data.nasional || data.tren || Object.values(data).find(v => Array.isArray(v)) || []);

                    const ctxNasional = document.getElementById('chartTrenNasional').getContext('2d');
                    if (chartTrenNasionalInstance) chartTrenNasionalInstance.destroy();
                    chartTrenNasionalInstance = new Chart(ctxNasional, {
                        type: 'line',
                        data: {
                            labels: listNasional.map(d => d.tahun),
                            datasets: [{
                                label: 'Rata-rata Nasional',
                                data: listNasional.map(d => getNilaiUmp(d)),
                                borderColor: '#16a34a',
                                backgroundColor: 'rgba(22, 163, 74, 0.12)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.35,
                                pointRadius: 5,
                                pointBackgroundColor: '#16a34a'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { ticks: { callback: val => 'Rp ' + (val / 1000000).toFixed(1) + ' Jt' } }
                            }
                        }
                    });
                } else {
                    const list = Array.isArray(data)
                        ? data
                        : (data.tren || data.riwayat || data.data || Object.values(data).find(v => Array.isArray(v)) || []);

                    const ctxProv = document.getElementById('chartTrenProvinsi').getContext('2d');
                    if (chartTrenProvinsiInstance) chartTrenProvinsiInstance.destroy();
                    chartTrenProvinsiInstance = new Chart(ctxProv, {
                        type: 'line',
                        data: {
                            labels: list.map(d => d.tahun),
                            datasets: [{
                                label: 'UMP Provinsi',
                                data: list.map(d => getNilaiUmp(d)),
                                borderColor: '#0284c7',
                                backgroundColor: 'rgba(2, 132, 199, 0.12)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.35,
                                pointRadius: 5,
                                pointBackgroundColor: '#0284c7'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { ticks: { callback: val => 'Rp ' + (val / 1000000).toFixed(1) + ' Jt' } }
                            }
                        }
                    });
                }
            } catch (err) {
                console.error('Gagal memuat tren:', err);
            }
        }

        // 4. Load Daftar Provinsi untuk Dropdown Tren
        async function loadDropdownProvinsi() {
            try {
                const res = await fetch('/api/ump?tahun=2026&per_page=100');
                const json = await res.json();
                const items = json.data?.data || json.data || [];
                const select = document.getElementById('filter-provinsi-tren');

                select.innerHTML = items.map(item => {
                    const prov = item.provinsi || {};
                    return `<option value="${prov.id || item.provinsi_id}">${getNamaProvinsi(item)}</option>`;
                }).join('');

                if (select.value) {
                    loadTren(select.value);
                }
            } catch (err) {
                console.error('Gagal memuat daftar provinsi:', err);
            }
        }

        // 5. Load Tabel UMP khusus Dashboard (mewarisi bahasa & paginasi dari app.blade.php)
        async function loadDataTableUmp(tahun) {
            try {
                const res = await fetch(`/api/ump?tahun=${tahun}&per_page=100`);
                const json = await res.json();
                const rows = json.data?.data || json.data || [];

                if (umpDataTable) {
                    umpDataTable.destroy();
                }

                const formattedData = rows.map((row, idx) => [
                    idx + 1,
                    getNamaProvinsi(row) || '-',
                    row.tahun,
                    getNilaiUmp(row)
                ]);

                umpDataTable = $('#tabelUmpDataTable').DataTable({
                    data: formattedData,
                    language: {
                        search: 'Cari Provinsi:',
                        searchPlaceholder: 'Ketik nama provinsi...',
                        info: 'Menampilkan _START_ - _END_ dari _TOTAL_ provinsi',
                        infoEmpty: 'Tidak ada data UMP',
                        zeroRecords: 'Provinsi tidak ditemukan'
                    },
                    columnDefs: [
                        { targets: 0, className: 'py-3.5 px-4 font-bold text-slate-500' },
                        { targets: 1, className: 'py-3.5 px-4 font-bold text-slate-800' },
                        {
                            targets: 2,
                            className: 'py-3.5 px-4',
                            render: (data) => `<span class="badge-pill bg-slate-100 text-slate-700">${data}</span>`
                        },
                        {
                            targets: 3,
                            className: 'py-3.5 px-4 text-right font-extrabold text-emerald-700',
                            render: (data, type) => type === 'display' ? formatRupiah(data) : Number(data)
                        }
                    ]
                });
            } catch (err) {
                console.error('Gagal memuat DataTables UMP:', err);
            }
        }

        // Event Listeners
        document.addEventListener('DOMContentLoaded', () => {
            const tahunAwal = document.getElementById('filter-tahun').value;
            loadStatistik(tahunAwal);
            loadPerbandingan(tahunAwal);
            loadTren();
            loadDropdownProvinsi();
            loadDataTableUmp(tahunAwal);

            document.getElementById('filter-tahun').addEventListener('change', (e) => {
                const t = e.target.value;
                loadStatistik(t);
                loadPerbandingan(t);
                loadDataTableUmp(t);
            });

            document.getElementById('filter-provinsi-tren').addEventListener('change', (e) => {
                if (e.target.value) loadTren(e.target.value);
            });
        });
    </script>
@endpush