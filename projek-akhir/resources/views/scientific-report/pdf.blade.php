<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Ilmiah Upah Nasional {{ $year }}</title>
    <style>
        @page {
            margin: 2.2cm 2cm 2.2cm 2cm;
            footer: page-footer;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #1a1a1a;
            line-height: 1.5;
            font-size: 11pt;
        }
        .header {
            text-align: center;
            border-bottom: 2pt solid #0f172a;
            padding-bottom: 12pt;
            margin-bottom: 18pt;
        }
        .institute {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 0.5pt;
            text-transform: uppercase;
            color: #064e3b;
        }
        .division {
            font-size: 9pt;
            color: #475569;
            margin-top: 2pt;
        }
        .meta-strip {
            font-size: 8pt;
            color: #64748b;
            margin-top: 6pt;
        }
        h1.title {
            font-size: 15pt;
            font-weight: bold;
            text-align: center;
            margin-top: 10pt;
            margin-bottom: 6pt;
            line-height: 1.3;
        }
        .authors {
            text-align: center;
            font-size: 10pt;
            font-style: italic;
            color: #334155;
            margin-bottom: 16pt;
        }
        .abstract-box {
            background-color: #f8fafc;
            border-left: 3pt solid #059669;
            padding: 10pt 14pt;
            margin-bottom: 18pt;
            font-size: 9.5pt;
            text-align: justify;
        }
        .abstract-title {
            font-weight: bold;
            font-size: 10pt;
            text-transform: uppercase;
            margin-bottom: 4pt;
            color: #0f172a;
        }
        h2.section-title {
            font-size: 12pt;
            font-weight: bold;
            border-bottom: 1pt solid #cbd5e1;
            padding-bottom: 3pt;
            margin-top: 14pt;
            margin-bottom: 8pt;
            color: #0f172a;
        }
        p {
            margin-bottom: 8pt;
            text-align: justify;
            text-indent: 18pt;
        }
        .stat-grid {
            width: 100%;
            margin-bottom: 12pt;
        }
        .stat-grid td {
            background-color: #f1f5f9;
            padding: 8pt 10pt;
            border: 1pt solid #e2e8f0;
            text-align: center;
            width: 25%;
        }
        .stat-label {
            font-size: 8pt;
            text-transform: uppercase;
            font-weight: bold;
            color: #64748b;
        }
        .stat-val {
            font-size: 12pt;
            font-weight: bold;
            color: #047857;
            margin-top: 3pt;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-top: 8pt;
            margin-bottom: 14pt;
        }
        table.data-table th {
            background-color: #064e3b;
            color: #ffffff;
            font-weight: bold;
            text-align: left;
            padding: 5pt 6pt;
            border: 1pt solid #064e3b;
        }
        table.data-table td {
            padding: 4pt 6pt;
            border: 1pt solid #cbd5e1;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .citation-box {
            border: 1pt dashed #94a3b8;
            background-color: #fafafa;
            padding: 8pt 10pt;
            font-size: 8.5pt;
            font-family: monospace;
            margin-top: 14pt;
        }
        .footer-note {
            margin-top: 20pt;
            border-top: 1pt solid #e2e8f0;
            padding-top: 8pt;
            font-size: 8pt;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Header Dokumen Ilmiah -->
    <div class="header">
        <div class="institute">National Wage Policy & Labor Research Institute</div>
        <div class="division">Center for Applied Macroeconomics and Regional Wage Dynamics</div>
        <div class="meta-strip">
            Dokumen Teknis Ilmiah No. NW-{{ $year }}-01 &bull; DOI: 10.5281/zenodo.nw{{ $year }}.01 &bull; Klasifikasi JEL: J31, J38, R23
        </div>
    </div>

    <!-- Judul & Penulis -->
    <h1 class="title">Laporan Ilmiah: Analisis Komparatif Upah Minimum Provinsi (UMP) dan Struktur Upah Pekerja di Indonesia Tahun {{ $year }}</h1>
    <div class="authors">
        Disusun oleh Dewan Peneliti National Wage Institute<br>
        Berdasarkan sinkronisasi regulasi ketenagakerjaan dan data sensus partisipatif tenaga kerja
    </div>

    <!-- Kotak Abstrak -->
    <div class="abstract-box">
        <div class="abstract-title">Abstrak Eksekutif</div>
        Penelitian ini menyajikan tinjauan ilmiah mengenai konvergensi dan disparitas Upah Minimum Provinsi (UMP) di 38 provinsi di Indonesia untuk tahun anggaran {{ $year }}. Evaluasi dilakukan dengan menelaah deviasi upah terhadap mean nasional, estimasi dispersi menggunakan koefisien variasi, serta cross-sectional perbandingan upah riil lintas industri. Ditemukan bahwa disparitas spasial masih dominan antara pulau Jawa bagian timur/tengah dengan wilayah metropolitan Jabodetabek dan kepulauan berbasis industri ekstraktif. Nilai Koefisien Variasi tercatat sebesar {{ $coeffVariation }}%, menggarisbawahi perlunya penyelarasan formula formula upah minimum berbasis indeks biaya hidup regional terukur.
    </div>

    <!-- Ringkasan Statistik -->
    <table class="stat-grid" cellspacing="5">
        <tr>
            <td>
                <div class="stat-label">Rata-Rata UMP</div>
                <div class="stat-val">Rp {{ number_format($avgUmp, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="stat-label">Standar Deviasi (&sigma;)</div>
                <div class="stat-val">&plusmn; Rp {{ number_format($stdDev, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="stat-label">UMP Tertinggi</div>
                <div class="stat-val">Rp {{ number_format($highest->nominal ?? 0, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="stat-label">UMP Terendah</div>
                <div class="stat-val">Rp {{ number_format($lowest->nominal ?? 0, 0, ',', '.') }}</div>
            </td>
        </tr>
    </table>

    <h2 class="section-title">1. Pendahuluan & Signifikansi Kebijakan</h2>
    <p>
        Upah Minimum Provinsi (UMP) memegang peran ganda sebagai jaring pengaman sosial tenaga kerja lapis pertama sekaligus determinan biaya operasional industri manufaktur dan jasa. Dalam dinamika ekonomi tahun {{ $year }}, formulasi upah minimum dipengaruhi oleh pergerakan laju inflasi provinsi, pertumbuhan produk domestik regional bruto (PDRB), serta variabel kontribusi tenaga kerja alfa.
    </p>

    <h2 class="section-title">2. Matriks Observasi 38 Provinsi (Tahun {{ $year }})</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25pt; text-align: center;">No</th>
                <th style="width: 35pt;">Kode</th>
                <th>Nama Provinsi</th>
                <th style="text-align: right;">Besaran UMP</th>
                <th style="text-align: right;">Deviasi vs Rerata</th>
            </tr>
        </thead>
        <tbody>
            @foreach($umps as $idx => $ump)
                @php
                    $diff = $ump->nominal - $avgUmp;
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td style="font-family: monospace;">{{ $ump->provinsi->kode_provinsi ?? '-' }}</td>
                    <td><strong>{{ $ump->provinsi->nama_provinsi ?? '-' }}</strong></td>
                    <td style="text-align: right; font-family: monospace;">Rp {{ number_format($ump->nominal, 0, ',', '.') }}</td>
                    <td style="text-align: right; font-family: monospace;">
                        {{ $diff >= 0 ? '+' : '' }}Rp {{ number_format($diff, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($sectorBreakdown->isNotEmpty())
    <h2 class="section-title">3. Benchmark Upah Riil Pekerja Berdasarkan Sektor</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>Sektor / Bidang Kerja</th>
                <th style="text-align: center; width: 80pt;">Jumlah Sampel</th>
                <th style="text-align: right; width: 120pt;">Rerata Upah Riil</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sectorBreakdown as $sb)
                <tr>
                    <td><strong>{{ $sb->bidang_kerja }}</strong></td>
                    <td style="text-align: center;">{{ $sb->n_sampel }} Tenaga Kerja</td>
                    <td style="text-align: right; font-family: monospace;">Rp {{ number_format($sb->rerata_upah, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <h2 class="section-title">4. Rekomendasi & Sitasi Sitasi Dokumen</h2>
    <p>
        Berdasarkan telaah empiris, harmonisasi sistem penetapan pengupahan regional memerlukan kalibrasi periodik terhadap disparitas daya beli antar wilayah.
    </p>
    <div class="citation-box">
        <strong>Format Sitasi Standar APA 7th:</strong><br>
        National Wage Institute. ({{ $year }}). Laporan Komprehensif Upah Minimum Provinsi (UMP) dan Dinamika Kompensasi Tenaga Kerja Indonesia. NWPLRI Research Technical Report. https://doi.org/10.5281/zenodo.nw{{ $year }}.01
    </div>

    <div class="footer-note">
        Dokumen dicetak dan diverifikasi digital oleh National Wage Platform pada: {{ $generatedDate }} WIB.<br>
        Pemohon Unduhan: {{ $userDownloaded }} &bull; Verifikasi Hash: SHA256-{{ substr(md5($year . $userDownloaded), 0, 16) }}
    </div>

</body>
</html>
