@extends('layouts.app')

@section('title', 'Service API & Endpoint Fleksibel')

@section('content')
<div class="space-y-8">

    <!-- Header Service API -->
    <section class="card-tactile p-6 sm:p-8 bg-white border-t-8 border-t-sky-600 space-y-3">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="badge-pill bg-sky-100 text-sky-800 text-xs font-bold font-mono">
                    <i class="fa-solid fa-bolt mr-1 text-sky-600"></i> RESTful GET API v1
                </span>
                <span class="badge-pill bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                    Publik & Fleksibel (Single Endpoint)
                </span>
            </div>
            <a href="{{ route('service-api.index', ['format' => 'json']) }}" target="_blank"
               class="btn-tactile-secondary px-3.5 py-1.5 text-xs inline-flex items-center gap-1.5 font-mono">
                <i class="fa-solid fa-arrow-up-right-from-square text-sky-600"></i>
                <span>Buka Raw JSON (/service-api?format=json)</span>
            </a>
        </div>

        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
            Dokumentasi &amp; Playground Service API Upah Nasional
        </h1>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-3xl">
            Satu endpoint tunggal (<code>GET /service-api</code>) yang dirancang fleksibel untuk mengambil data Upah Minimum Provinsi (UMP) resmi, riwayat sensus upah pekerja riil, maupun ringkasan agregat dengan filter kustom tanpa batas.
        </p>
    </section>

    <!-- INTERACTIVE API PLAYGROUND / LIVE TESTER -->
    <section class="card-tactile p-6 bg-white space-y-5">
        <div class="flex items-center justify-between border-b-2 border-slate-100 pb-3">
            <div>
                <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-terminal text-sky-600"></i>
                    API Playground (Live Request Tester)
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Uji coba parameter langsung dan lihat respon JSON seketika</p>
            </div>
            <span class="badge-pill bg-slate-100 text-slate-700 font-mono text-[11px]">GET /service-api</span>
        </div>

        <!-- Query Builder Form -->
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 bg-slate-50 p-4 rounded-2xl border-2 border-slate-200 text-xs">
            
            <!-- Type -->
            <div>
                <label class="block font-bold uppercase text-slate-600 mb-1">Tipe Data (type)</label>
                <select id="paramType" onchange="runLiveTest()" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl font-semibold">
                    <option value="ump">ump (Data UMP Resmi)</option>
                    <option value="pekerja">pekerja (Upah Riil Pekerja)</option>
                    <option value="all">all (Ringkasan Agregat)</option>
                </select>
            </div>

            <!-- Provinsi -->
            <div>
                <label class="block font-bold uppercase text-slate-600 mb-1">Provinsi (provinsi)</label>
                <select id="paramProvinsi" onchange="runLiveTest()" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl font-semibold">
                    <option value="">-- Semua Provinsi --</option>
                    @foreach($provinsis as $p)
                        <option value="{{ $p->nama_provinsi }}">{{ $p->nama_provinsi }} ({{ $p->kode_provinsi }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Tahun -->
            <div>
                <label class="block font-bold uppercase text-slate-600 mb-1">Tahun (tahun)</label>
                <select id="paramTahun" onchange="runLiveTest()" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl font-semibold">
                    <option value="">-- Semua Tahun --</option>
                    @foreach($years as $yr)
                        <option value="{{ $yr }}" {{ $yr == 2026 ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label class="block font-bold uppercase text-slate-600 mb-1">Urutan (sort)</label>
                <select id="paramSort" onchange="runLiveTest()" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl font-semibold">
                    <option value="nominal_desc">nominal_desc (Upah Tertinggi)</option>
                    <option value="nominal_asc">nominal_asc (Upah Terendah)</option>
                    <option value="tahun_desc">tahun_desc (Tahun Terbaru)</option>
                    <option value="nama_asc">nama_asc (Nama Provinsi A-Z)</option>
                </select>
            </div>

            <!-- Limit -->
            <div>
                <label class="block font-bold uppercase text-slate-600 mb-1">Limit (limit)</label>
                <input type="number" id="paramLimit" value="5" min="1" max="100" onchange="runLiveTest()"
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl font-semibold">
            </div>

            <!-- Min Nominal -->
            <div>
                <label class="block font-bold uppercase text-slate-600 mb-1">Min Nominal (min_nominal)</label>
                <input type="number" id="paramMinNominal" placeholder="Contoh: 3000000" onchange="runLiveTest()"
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl font-semibold">
            </div>

            <!-- Sektor -->
            <div class="sm:col-span-2">
                <label class="block font-bold uppercase text-slate-600 mb-1">Sektor / Bidang (sektor)</label>
                <input type="text" id="paramSektor" placeholder="Contoh: Teknologi, Manufaktur (untuk type=pekerja)" oninput="runLiveTest()"
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl font-semibold">
            </div>

        </div>

        <!-- Request URL Preview Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-slate-900 text-white px-4 py-2.5 rounded-xl font-mono text-xs">
            <div class="flex items-center gap-2 overflow-x-auto">
                <span class="bg-emerald-500 text-slate-950 font-bold px-2 py-0.5 rounded text-[10px]">GET</span>
                <span id="requestUrlPreview" class="text-emerald-300 truncate">/service-api?format=json</span>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <button type="button" onclick="runLiveTest()" class="px-3 py-1 bg-sky-600 hover:bg-sky-500 text-white rounded-lg text-xs font-bold transition flex items-center gap-1">
                    <i class="fa-solid fa-play text-[10px]"></i>
                    <span>Eksekusi</span>
                </button>
                <button type="button" onclick="copyApiUrl()" id="btnCopyUrl" class="px-2.5 py-1 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg text-xs font-bold transition">
                    <i class="fa-regular fa-copy"></i>
                </button>
            </div>
        </div>

        <!-- Live Response Output Box -->
        <div class="relative bg-slate-950 text-slate-200 rounded-2xl p-4 overflow-hidden border border-slate-800">
            <div class="flex items-center justify-between mb-2 text-xs text-slate-400 border-b border-slate-800 pb-2">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                    <span id="responseStatusBadge" class="font-bold text-emerald-400">STATUS: 200 OK</span>
                    <span id="responseTime" class="text-slate-500"></span>
                </div>
                <button type="button" onclick="copyResponseJson()" id="btnCopyJson" class="text-slate-400 hover:text-white transition flex items-center gap-1">
                    <i class="fa-regular fa-copy text-xs"></i>
                    <span id="copyJsonLabel">Salin JSON</span>
                </button>
            </div>
            <pre id="jsonOutput" class="font-mono text-xs overflow-x-auto max-h-96 leading-relaxed text-emerald-400">Memuat data respon...</pre>
        </div>
    </section>

    <!-- SECTION: SPESIFIKASI PARAMETER FILTER -->
    <section class="card-tactile p-6 bg-white space-y-4">
        <div>
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-sliders text-emerald-600"></i>
                Parameter Filter yang Didukung (Query Parameters)
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Seluruh parameter di bawah bersifat opsional dan dapat dikombinasikan secara bebas.</p>
        </div>

        <div class="overflow-x-auto rounded-2xl border-2 border-slate-200">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-100/90 border-b-2 border-slate-200 text-slate-600 font-extrabold uppercase font-mono">
                    <tr>
                        <th class="py-3 px-4">Parameter</th>
                        <th class="py-3 px-4">Tipe Data</th>
                        <th class="py-3 px-4">Contoh Nilai</th>
                        <th class="py-3 px-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    <tr>
                        <td class="py-2.5 px-4 font-mono font-bold text-sky-700">type</td>
                        <td class="py-2.5 px-4 font-mono text-slate-500">string</td>
                        <td class="py-2.5 px-4 font-mono">ump | pekerja | all</td>
                        <td class="py-2.5 px-4">Menentukan jenis basis data yang ditarik. Default: <code>ump</code>.</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-4 font-mono font-bold text-sky-700">provinsi</td>
                        <td class="py-2.5 px-4 font-mono text-slate-500">string</td>
                        <td class="py-2.5 px-4 font-mono">DKI Jakarta, Jawa Barat</td>
                        <td class="py-2.5 px-4">Pencarian wilayah provinsi secara parsial (case-insensitive).</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-4 font-mono font-bold text-sky-700">kode_provinsi</td>
                        <td class="py-2.5 px-4 font-mono text-slate-500">string</td>
                        <td class="py-2.5 px-4 font-mono">31, 32, 35</td>
                        <td class="py-2.5 px-4">Filter tepat berdasarkan 2 digit kode resmi provinsi BPS.</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-4 font-mono font-bold text-sky-700">tahun</td>
                        <td class="py-2.5 px-4 font-mono text-slate-500">integer</td>
                        <td class="py-2.5 px-4 font-mono">2026, 2025</td>
                        <td class="py-2.5 px-4">Filter tahun tunggal secara spesifik.</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-4 font-mono font-bold text-sky-700">tahun_dari &amp; tahun_sampai</td>
                        <td class="py-2.5 px-4 font-mono text-slate-500">integer</td>
                        <td class="py-2.5 px-4 font-mono">tahun_dari=2020&amp;tahun_sampai=2026</td>
                        <td class="py-2.5 px-4">Rentang tahun minimum dan maksimum untuk analisis tren.</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-4 font-mono font-bold text-sky-700">sektor / bidang_kerja</td>
                        <td class="py-2.5 px-4 font-mono text-slate-500">string</td>
                        <td class="py-2.5 px-4 font-mono">Teknologi, Manufaktur</td>
                        <td class="py-2.5 px-4">Filter sektor industri (khusus pada <code>type=pekerja</code>).</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-4 font-mono font-bold text-sky-700">min_nominal &amp; max_nominal</td>
                        <td class="py-2.5 px-4 font-mono text-slate-500">numeric</td>
                        <td class="py-2.5 px-4 font-mono">min_nominal=3000000</td>
                        <td class="py-2.5 px-4">Batas bawah dan atas nominal upah kerja (dalam Rupiah).</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-4 font-mono font-bold text-sky-700">sort</td>
                        <td class="py-2.5 px-4 font-mono text-slate-500">string</td>
                        <td class="py-2.5 px-4 font-mono">nominal_desc | nominal_asc | tahun_desc</td>
                        <td class="py-2.5 px-4">Kriteria pengurutan baris data.</td>
                    </tr>
                    <tr>
                        <td class="py-2.5 px-4 font-mono font-bold text-sky-700">limit / per_page</td>
                        <td class="py-2.5 px-4 font-mono text-slate-500">integer</td>
                        <td class="py-2.5 px-4 font-mono">10, 25, 50 (maks 100)</td>
                        <td class="py-2.5 px-4">Jumlah entri data per halaman respon.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>

    <!-- SECTION: CONTOH KODE INTEGRASI (CURL, JS, PHP, PYTHON) -->
    <section class="card-tactile p-6 bg-white space-y-4">
        <div>
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-code text-emerald-600"></i>
                Contoh Snippet Integrasi Kode Pemrograman
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Gunakan cuplikan kode di bawah untuk mengonsumsi API pada aplikasi Anda.</p>
        </div>

        <div class="flex items-center gap-2 border-b-2 border-slate-100 pb-2">
            <button type="button" onclick="switchCodeSnippet('curl')" id="tab-curl" class="code-tab px-3 py-1 rounded-xl text-xs font-bold bg-slate-900 text-white transition">cURL</button>
            <button type="button" onclick="switchCodeSnippet('js')" id="tab-js" class="code-tab px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">JavaScript (Fetch)</button>
            <button type="button" onclick="switchCodeSnippet('php')" id="tab-php" class="code-tab px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">PHP (cURL)</button>
            <button type="button" onclick="switchCodeSnippet('python')" id="tab-python" class="code-tab px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition">Python (requests)</button>
        </div>

        <div class="bg-slate-900 text-slate-200 rounded-2xl p-4 font-mono text-xs overflow-x-auto">
            <pre id="codeSnippetBox" class="leading-relaxed text-sky-300"></pre>
        </div>
    </section>

</div>

@push('scripts')
<script>
    const baseUrl = "{{ url('/service-api') }}";

    function buildQueryUrl() {
        const params = new URLSearchParams();
        params.append('format', 'json');

        const type = document.getElementById('paramType').value;
        if (type) params.append('type', type);

        const prov = document.getElementById('paramProvinsi').value;
        if (prov) params.append('provinsi', prov);

        const yr = document.getElementById('paramTahun').value;
        if (yr) params.append('tahun', yr);

        const sort = document.getElementById('paramSort').value;
        if (sort) params.append('sort', sort);

        const limit = document.getElementById('paramLimit').value;
        if (limit) params.append('limit', limit);

        const minNominal = document.getElementById('paramMinNominal').value;
        if (minNominal) params.append('min_nominal', minNominal);

        const sektor = document.getElementById('paramSektor').value;
        if (sektor) params.append('sektor', sektor);

        return baseUrl + '?' + params.toString();
    }

    function runLiveTest() {
        const url = buildQueryUrl();
        document.getElementById('requestUrlPreview').textContent = url.replace(window.location.origin, '');

        const startTime = performance.now();
        document.getElementById('jsonOutput').textContent = "Memuat data respon...";

        fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(res => {
                const duration = Math.round(performance.now() - startTime);
                document.getElementById('responseTime').textContent = `(${duration} ms)`;
                document.getElementById('responseStatusBadge').textContent = `STATUS: ${res.status} ${res.statusText}`;
                return res.json();
            })
            .then(data => {
                document.getElementById('jsonOutput').textContent = JSON.stringify(data, null, 2);
            })
            .catch(err => {
                document.getElementById('jsonOutput').textContent = "Gagal memuat respon: " + err.message;
            });
    }

    function copyApiUrl() {
        const url = buildQueryUrl();
        navigator.clipboard.writeText(url).then(() => {
            alert('URL endpoint berhasil disalin ke clipboard!');
        });
    }

    function copyResponseJson() {
        const jsonText = document.getElementById('jsonOutput').textContent;
        navigator.clipboard.writeText(jsonText).then(() => {
            const label = document.getElementById('copyJsonLabel');
            label.textContent = "Disalin!";
            setTimeout(() => label.textContent = "Salin JSON", 2000);
        });
    }

    const snippets = {
        curl: 'curl -X GET "' + baseUrl + '?format=json&type=ump&tahun=2026&limit=10" \\\n  -H "Accept: application/json"',
        js: '// Mengambil data upah dengan fetch API\n' +
            'const url = "' + baseUrl + '?format=json&type=ump&tahun=2026&sort=nominal_desc";\n\n' +
            'fetch(url, {\n' +
            '  method: "GET",\n' +
            '  headers: { "Accept": "application/json" }\n' +
            '})\n' +
            '.then(response => response.json())\n' +
            '.then(data => {\n' +
            '  console.log("Total Hasil:", data.meta.total_results);\n' +
            '  console.log("Data Upah:", data.data);\n' +
            '});',
        php: '// Contoh PHP cURL GET request\n' +
            '$endpoint = "' + baseUrl + '?format=json&type=ump&tahun=2026";\n\n' +
            '$ch = curl_init($endpoint);\n' +
            'curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);\n' +
            'curl_setopt($ch, CURLOPT_HTTPHEADER, ["Accept: application/json"]);\n' +
            '$response = curl_exec($ch);\n' +
            'curl_close($ch);\n\n' +
            '$result = json_decode($response, true);\n' +
            'print_r($result["data"]);',
        python: '# Contoh Python requests GET\n' +
            'import requests\n\n' +
            'url = "' + baseUrl + '"\n' +
            'params = {\n' +
            '    "format": "json",\n' +
            '    "type": "ump",\n' +
            '    "tahun": 2026,\n' +
            '    "limit": 10\n' +
            '}\n\n' +
            'response = requests.get(url, params=params, headers={"Accept": "application/json"})\n' +
            'data = response.json()\n\n' +
            'print(f"Total: {data[\'meta\'][\'total_results\']}")\n' +
            'for item in data[\'data\']:\n' +
            '    print(f"{item[\'provinsi\'][\'nama\']}: {item[\'nominal_formatted\']}")'
    };

    function switchCodeSnippet(lang) {
        document.getElementById('codeSnippetBox').textContent = snippets[lang];
        document.querySelectorAll('.code-tab').forEach(t => {
            t.className = "code-tab px-3 py-1 rounded-xl text-xs font-bold bg-slate-100 text-slate-600 hover:bg-slate-200 transition";
        });
        document.getElementById('tab-' + lang).className = "code-tab px-3 py-1 rounded-xl text-xs font-bold bg-slate-900 text-white transition";
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        runLiveTest();
        switchCodeSnippet('curl');
    });
</script>
@endpush
@endsection
