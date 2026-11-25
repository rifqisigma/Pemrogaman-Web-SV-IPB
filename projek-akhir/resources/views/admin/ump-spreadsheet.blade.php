<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import & Export Spreadsheet UMP</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; background: #f8fafc; color: #0f172a; }
        .container { max-width: 820px; margin: 0 auto; background: #ffffff; padding: 24px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); }
        h1 { margin-top: 0; font-size: 22px; }
        h2 { font-size: 16px; margin-bottom: 10px; }
        .box { border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 20px; background: #fcfcfd; }
        .alert-success { background: #dcfce7; color: #166534; padding: 12px 14px; border-radius: 6px; margin-bottom: 16px; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; padding: 12px 14px; border-radius: 6px; margin-bottom: 16px; border: 1px solid #fecaca; }
        .btn { display: inline-block; padding: 9px 16px; border-radius: 6px; text-decoration: none; border: none; cursor: pointer; font-size: 14px; font-weight: 600; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-success { background: #16a34a; color: #fff; margin-right: 8px; }
        .btn-secondary { background: #475569; color: #fff; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 14px; }
        th, td { border: 1px solid #cbd5e1; padding: 8px 10px; text-align: left; }
        th { background: #f1f5f9; }
        code { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Import &amp; Export Spreadsheet UMP</h1>
        <p>Kelola data Upah Minimum Provinsi (UMP) melalui file Excel (<code>.xlsx</code>/<code>.xls</code>) atau CSV (<code>.csv</code>).</p>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="box">
            <h2>1. Export Data UMP</h2>
            <p>Unduh seluruh data UMP yang tersimpan saat ini:</p>
            <a href="{{ route('admin.ump.spreadsheet.export', ['format' => 'xlsx']) }}" class="btn btn-success">
                Download Excel (.xlsx)
            </a>
            <a href="{{ route('admin.ump.spreadsheet.export', ['format' => 'csv']) }}" class="btn btn-secondary">
                Download CSV (.csv)
            </a>
        </div>

        <div class="box">
            <h2>2. Import Data UMP</h2>
            <form action="{{ route('admin.ump.spreadsheet.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <p>
                    <label for="file">Pilih file (.xlsx, .xls, .csv):</label><br><br>
                    <input type="file" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                </p>
                <button type="submit" class="btn btn-primary">Import Data</button>
            </form>
        </div>

        <div class="box">
            <h2>3. Format Kolom Template</h2>
            <p>Pastikan baris pertama (header) file import menggunakan nama kolom berikut:</p>
            <table>
                <thead>
                    <tr>
                        <th>kode_provinsi</th>
                        <th>nama_provinsi</th>
                        <th>tahun</th>
                        <th>nominal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>32</td>
                        <td>Jawa Barat</td>
                        <td>2026</td>
                        <td>2191238</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>