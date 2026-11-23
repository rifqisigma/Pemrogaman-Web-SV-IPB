<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Statistik UMP Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-indigo-600 text-white font-bold flex items-center justify-center">UMP</div>
                <div>
                    <h1 class="font-bold text-slate-900 leading-tight">Dashboard Upah Minimum Provinsi</h1>
                    <p class="text-xs text-slate-500">Analisis & Monitoring Data UMP Nasional</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <select id="filterTahun" class="rounded-lg border-slate-300 border px-3 py-1.5 text-sm bg-white focus:ring-2 focus:ring-indigo-500">
                    <option value="">Memuat Tahun...</option>
                </select>
                <select id="filterProvinsi" class="rounded-lg border-slate-300 border px-3 py-1.5 text-sm bg-white focus:ring-2 focus:ring-indigo-500 max-w-[220px]">
                    <option value="">Memuat Provinsi...</option>
                </select>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- 4 Kartu Ringkasan -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-medium text-slate-500 uppercase">UMP Tertinggi</p>
                <h2 id="cardTertinggiNominal" class="text-2xl font-bold text-emerald-600 mt-1">-</h2>
                <p id="cardTertinggiProvinsi" class="text-xs text-slate-500 mt-1">Memuat...</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-medium text-slate-500 uppercase">UMP Terendah</p>
                <h2 id="cardTerendahNominal" class="text-2xl font-bold text-rose-600 mt-1">-</h2>
                <p id="cardTerendahProvinsi" class="text-xs text-slate-500 mt-1">Memuat...</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-medium text-slate-500 uppercase">Rata-Rata Nasional</p>
                <h2 id="cardRataRata" class="text-2xl font-bold text-indigo-600 mt-1">-</h2>
                <p id="cardTahunLabel" class="text-xs text-slate-500 mt-1">Tahun -</p>
            </div>
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <p class="text-xs font-medium text-slate-500 uppercase">Total Provinsi</p>
                <h2 id="cardTotalProvinsi" class="text-2xl font-bold text-slate-800 mt-1">-</h2>
                <p class="text-xs text-slate-500 mt-1">Wilayah Terdaftar</p>
            </div>
        </section>

        <!-- Baris Grafik 1: Top 10 Tertinggi & Terendah -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <h3 class="font-semibold text-slate-800 mb-4">10 Provinsi UMP Tertinggi</h3>
                <div class="h-72"><canvas id="chartTop10"></canvas></div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <h3 class="font-semibold text-slate-800 mb-4">10 Provinsi UMP Terendah</h3>
                <div class="h-72"><canvas id="chartBottom10"></canvas></div>
            </div>
        </section>

        <!-- Baris Grafik 2: Tren Nasional & Tren Per Provinsi -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <h3 class="font-semibold text-slate-800 mb-4">Tren Rata-Rata UMP Nasional per Tahun</h3>
                <div class="h-72"><canvas id="chartTrenNasional"></canvas></div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <h3 id="judulTrenProvinsi" class="font-semibold text-slate-800 mb-4">Tren UMP Provinsi Pilihan</h3>
                <div class="h-72"><canvas id="chartTrenProvinsi"></canvas></div>
            </div>
        </section>

        <!-- Baris Grafik 3: Rentang Nasional & Perbandingan Seluruh Provinsi -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <h3 class="font-semibold text-slate-800 mb-4">Rentang UMP Tahunan (Min - Avg - Max)</h3>
                <div class="h-72"><canvas id="chartRentang"></canvas></div>
            </div>
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm lg:col-span-2">
                <h3 class="font-semibold text-slate-800 mb-4">Perbandingan Seluruh Provinsi</h3>
                <div class="h-72"><canvas id="chartSemuaProvinsi"></canvas></div>
            </div>
        </section>

        <!-- Tabel Data UMP -->
        <section class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="font-semibold text-slate-800">Daftar Data UMP</h3>
                    <p class="text-xs text-slate-500">Gunakan pencarian atau filter di atas untuk menyaring tabel</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="text" id="searchInput" placeholder="Cari nama provinsi..."
                        class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 w-64">
                    <button id="resetFilterBtn" class="px-3 py-2 text-xs font-medium bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg">
                        Reset
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-600 uppercase text-xs border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-3">No</th>
                            <th class="px-6 py-3">Kode</th>
                            <th class="px-6 py-3">Provinsi</th>
                            <th class="px-6 py-3">Tahun</th>
                            <th class="px-6 py-3 text-right">Nominal UMP</th>
                        </tr>
                    </thead>
                    <tbody id="tabelUmpBody" class="divide-y divide-slate-100">
                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-slate-200 flex items-center justify-between text-xs text-slate-600">
                <span id="paginationInfo">Menampilkan 0 data</span>
                <div class="flex items-center gap-2">
                    <button id="prevPage" class="px-3 py-1.5 rounded border border-slate-300 hover:bg-slate-50 disabled:opacity-40">Sebelumnya</button>
                    <span id="pageIndicator" class="font-medium">Hal 1</span>
                    <button id="nextPage" class="px-3 py-1.5 rounded border border-slate-300 hover:bg-slate-50 disabled:opacity-40">Berikutnya</button>
                </div>
            </div>
        </section>

    </main>

    <script>
        const formatRupiah = (angka) => new Intl.NumberFormat('id-ID', {
            style: 'currency', currency: 'IDR', maximumFractionDigits: 0
        }).format(angka || 0);

        const charts = {};
        let currentPage = 1;
        let lastPage = 1;
        let searchTimeout = null;

        function getHeaders() {
            const token = localStorage.getItem('token');
            const headers = { 'Accept': 'application/json' };
            if (token) headers['Authorization'] = `Bearer ${token}`;
            return headers;
        }

        function renderChart(id, type, labels, datasets, horizontal = false) {
            if (charts[id]) charts[id].destroy();
            const ctx = document.getElementById(id).getContext('2d');
            charts[id] = new Chart(ctx, {
                type,
                data: { labels, datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: horizontal ? 'y' : 'x',
                    plugins: { legend: { display: datasets.length > 1 } },
                    scales: {
                        x: { ticks: { font: { size: 10 } } },
                        y: { ticks: { font: { size: 10 } } }
                    }
                }
            });
        }

        async function loadProvinsiDropdown() {
            const res = await fetch('/api/provinsi', { headers: getHeaders() });
            const json = await res.json();
            const select = document.getElementById('filterProvinsi');
            select.innerHTML = '<option value="">Semua Provinsi (Tren: Jakarta)</option>';
            json.data.forEach(p => {
                select.innerHTML += `<option value="${p.id}">${p.nama_provinsi}</option>`;
            });
        }

        async function loadStatistik(tahun = '') {
            const url = tahun ? `/api/ump/statistik?tahun=${tahun}` : '/api/ump/statistik';
            const res = await fetch(url, { headers: getHeaders() });
            const { data } = await res.json();

            const selectTahun = document.getElementById('filterTahun');
            if (selectTahun.options.length <= 1 && data.tahun_tersedia.length) {
                selectTahun.innerHTML = '';
                data.tahun_tersedia.forEach(t => {
                    selectTahun.innerHTML += `<option value="${t}" ${t == data.tahun_aktif ? 'selected' : ''}>Tahun ${t}</option>`;
                });
            }

            document.getElementById('cardTertinggiNominal').textContent = data.tertinggi ? formatRupiah(data.tertinggi.nominal) : '-';
            document.getElementById('cardTertinggiProvinsi').textContent = data.tertinggi ? data.tertinggi.provinsi : '-';
            document.getElementById('cardTerendahNominal').textContent = data.terendah ? formatRupiah(data.terendah.nominal) : '-';
            document.getElementById('cardTerendahProvinsi').textContent = data.terendah ? data.terendah.provinsi : '-';
            document.getElementById('cardRataRata').textContent = formatRupiah(data.rata_rata);
            document.getElementById('cardTahunLabel').textContent = `Tahun ${data.tahun_aktif}`;
            document.getElementById('cardTotalProvinsi').textContent = `${data.total_provinsi} Provinsi`;

            return data.tahun_aktif;
        }

        async function loadPerbandingan(tahun) {
            const res = await fetch(`/api/ump/perbandingan?tahun=${tahun}`, { headers: getHeaders() });
            const { data } = await res.json();

            renderChart('chartTop10', 'bar',
                data.top_10.map(i => i.nama_provinsi),
                [{ label: 'UMP (Rp)', data: data.top_10.map(i => i.nominal), backgroundColor: '#10b981', borderRadius: 4 }],
                true
            );

            renderChart('chartBottom10', 'bar',
                data.bottom_10.map(i => i.nama_provinsi),
                [{ label: 'UMP (Rp)', data: data.bottom_10.map(i => i.nominal), backgroundColor: '#f43f5e', borderRadius: 4 }],
                true
            );

            renderChart('chartSemuaProvinsi', 'bar',
                data.semua.map(i => i.nama_provinsi),
                [{ label: `UMP ${tahun}`, data: data.semua.map(i => i.nominal), backgroundColor: '#6366f1', borderRadius: 3 }]
            );
        }

        async function loadTren(provinsiId = '') {
            const url = provinsiId ? `/api/ump/tren?provinsi_id=${provinsiId}` : '/api/ump/tren';
            const res = await fetch(url, { headers: getHeaders() });
            const { data } = await res.json();

            renderChart('chartTrenNasional', 'line',
                data.nasional.map(i => i.tahun),
                [{ label: 'Rata-rata Nasional', data: data.nasional.map(i => i.rata_rata), borderColor: '#4f46e5', backgroundColor: 'rgba(79,70,229,0.1)', fill: true, tension: 0.3 }]
            );

            document.getElementById('judulTrenProvinsi').textContent = `Tren UMP: ${data.provinsi.nama_provinsi || 'Provinsi Pilihan'}`;
            renderChart('chartTrenProvinsi', 'line',
                data.provinsi.riwayat.map(i => i.tahun),
                [{ label: data.provinsi.nama_provinsi, data: data.provinsi.riwayat.map(i => i.nominal), borderColor: '#0ea5e9', backgroundColor: 'rgba(14,165,233,0.1)', fill: true, tension: 0.3 }]
            );

            renderChart('chartRentang', 'line',
                data.nasional.map(i => i.tahun),
                [
                    { label: 'Tertinggi', data: data.nasional.map(i => i.tertinggi), borderColor: '#10b981', tension: 0.3 },
                    { label: 'Rata-rata', data: data.nasional.map(i => i.rata_rata), borderColor: '#6366f1', tension: 0.3 },
                    { label: 'Terendah', data: data.nasional.map(i => i.terendah), borderColor: '#f43f5e', tension: 0.3 },
                ]
            );
        }

        async function loadTabel(page = 1) {
            const tahun = document.getElementById('filterTahun').value;
            const provinsiId = document.getElementById('filterProvinsi').value;
            const search = document.getElementById('searchInput').value;

            const params = new URLSearchParams({ page, per_page: 10 });
            if (tahun) params.append('tahun', tahun);
            if (provinsiId) params.append('provinsi_id', provinsiId);
            if (search) params.append('search', search);

            const res = await fetch(`/api/ump?${params.toString()}`, { headers: getHeaders() });
            const json = await res.json();
            const tbody = document.getElementById('tabelUmpBody');

            if (!json.data || json.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-slate-400">Tidak ada data UMP ditemukan.</td></tr>';
                document.getElementById('paginationInfo').textContent = 'Menampilkan 0 data';
                return;
            }

            currentPage = json.meta.current_page;
            lastPage = json.meta.last_page;
            const startNo = (currentPage - 1) * json.meta.per_page + 1;

            tbody.innerHTML = json.data.map((row, idx) => `
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-3 text-slate-500">${startNo + idx}</td>
                    <td class="px-6 py-3 font-mono text-xs text-slate-600">${row.provinsi?.kode_provinsi ?? '-'}</td>
                    <td class="px-6 py-3 font-medium text-slate-800">${row.provinsi?.nama_provinsi ?? '-'}</td>
                    <td class="px-6 py-3 text-slate-600">${row.tahun}</td>
                    <td class="px-6 py-3 text-right font-semibold text-slate-900">${formatRupiah(row.nominal)}</td>
                </tr>
            `).join('');

            document.getElementById('paginationInfo').textContent = `Total ${json.meta.total} data ditemukan`;
            document.getElementById('pageIndicator').textContent = `Hal ${currentPage} dari ${lastPage}`;
            document.getElementById('prevPage').disabled = currentPage <= 1;
            document.getElementById('nextPage').disabled = currentPage >= lastPage;
        }

        document.getElementById('filterTahun').addEventListener('change', async (e) => {
            const tahun = e.target.value;
            await loadStatistik(tahun);
            await loadPerbandingan(tahun);
            await loadTabel(1);
        });

        document.getElementById('filterProvinsi').addEventListener('change', async (e) => {
            const provId = e.target.value;
            await loadTren(provId);
            await loadTabel(1);
        });

        document.getElementById('searchInput').addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => loadTabel(1), 350);
        });

        document.getElementById('resetFilterBtn').addEventListener('click', async () => {
            document.getElementById('filterProvinsi').value = '';
            document.getElementById('searchInput').value = '';
            await loadTren('');
            await loadTabel(1);
        });

        document.getElementById('prevPage').addEventListener('click', () => { if (currentPage > 1) loadTabel(currentPage - 1); });
        document.getElementById('nextPage').addEventListener('click', () => { if (currentPage < lastPage) loadTabel(currentPage + 1); });

        (async function init() {
            await loadProvinsiDropdown();
            const tahunAktif = await loadStatistik();
            await Promise.all([
                loadPerbandingan(tahunAktif),
                loadTren(),
                loadTabel(1)
            ]);
        })();
    </script>
</body>
</html>