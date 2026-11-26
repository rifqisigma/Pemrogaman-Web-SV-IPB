@extends('layouts.app')

@section('title', 'Admin Dashboard & Riwayat Log')

@section('content')
<div class="space-y-8">

    <section class="card-tactile p-6 bg-white flex flex-col md:flex-row md:items-center justify-between gap-5 border-l-8 border-l-amber-500">
        <div>
            <div class="flex items-center gap-2">
                <span class="badge-pill bg-amber-100 text-amber-900 border border-amber-300 font-extrabold text-xs">
                    <i class="fa-solid fa-shield-halved mr-1 text-amber-600"></i> MODE ADMINISTRATOR
                </span>
                <span class="text-xs text-slate-400">|</span>
                <span class="text-xs text-slate-500 font-bold">Audit Trail & Manajemen Upah Nasional</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1.5">
                Dashboard Kontrol & Riwayat Pengguna
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pantau log aktivitas pembaruan upah pekerja secara real-time, audit perubahan nominal, dan kelola dataset ketenagakerjaan.
            </p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.ump.spreadsheet') }}" class="btn-tactile-secondary px-4 py-2.5 text-xs inline-flex items-center gap-1.5">
                <i class="fa-solid fa-file-excel text-emerald-600"></i>
                <span>Import/Export UMP Excel</span>
            </a>
            <a href="{{ route('datasheet.index') }}" class="btn-tactile-secondary px-4 py-2.5 text-xs inline-flex items-center gap-1.5">
                <i class="fa-solid fa-table"></i>
                <span>Datasheet</span>
            </a>
        </div>
    </section>

    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="card-tactile p-5 bg-white">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Total Pekerja Terdaftar</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-slate-900">{{ $usersCount }} Pekerja</div>
            <p class="text-[11px] text-slate-400 mt-1">1 Akun Administrator Sistem</p>
        </div>

        <div class="card-tactile p-5 bg-white">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Entri Log Audit Upah</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-amber-700">{{ $logsCount }} Riwayat</div>
            <p class="text-[11px] text-slate-400 mt-1">Audit perubahan upah & alasan</p>
        </div>

        <div class="card-tactile p-5 bg-white">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Rerata Upah Riil Terkumpul</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-coins"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-emerald-700">Rp {{ number_format($workerAvg, 0, ',', '.') }}</div>
            <p class="text-[11px] text-slate-400 mt-1">Dari seluruh log laporan pekerja</p>
        </div>

        <div class="card-tactile p-5 bg-white">
            <div class="flex items-center justify-between text-slate-400 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider">Rerata UMP Nasional {{ $year }}</span>
                <div class="w-8 h-8 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-landmark"></i>
                </div>
            </div>
            <div class="text-2xl font-black text-sky-700">Rp {{ number_format($umpAvg, 0, ',', '.') }}</div>
            <p class="text-[11px] text-slate-400 mt-1">38 Provinsi seluruh Indonesia</p>
        </div>

    </section>

    <section class="card-tactile p-6 bg-white space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-amber-600"></i>
                    Riwayat Pengguna & Audit Log Pembaruan Upah
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Menampilkan data jejak waktu ketika pengguna memperbarui upah kerja (kapan diperbarui, mengapa diperbarui, nominal baru vs lama).
                </p>
            </div>
            <span class="badge-pill bg-amber-50 text-amber-800 border border-amber-200 text-xs">
                Total {{ $logs->total() }} Log Audit
            </span>
        </div>

        <form action="{{ route('admin.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3 bg-slate-50 p-3.5 rounded-2xl border-2 border-slate-200">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, jabatan, atau alasan..."
                       class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:border-amber-500 focus:outline-none">
            </div>

            <div>
                <select name="provinsi_id" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:border-amber-500 focus:outline-none">
                    <option value="">-- Semua Provinsi --</option>
                    @foreach($provinces as $p)
                        <option value="{{ $p->id }}" {{ request('provinsi_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_provinsi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="tahun" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-medium focus:border-amber-500 focus:outline-none">
                    <option value="">-- Semua Tahun --</option>
                    @foreach($years as $yr)
                        <option value="{{ $yr }}" {{ request('tahun') == $yr ? 'selected' : '' }}>Tahun {{ $yr }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="btn-tactile-secondary px-4 py-2 text-xs flex-1 text-center">
                    <i class="fa-solid fa-filter mr-1"></i> Saring
                </button>
                @if(request()->hasAny(['search', 'provinsi_id', 'tahun']))
                    <a href="{{ route('admin.index') }}" class="px-3 py-2 text-xs font-bold text-slate-500 hover:text-slate-800 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto rounded-2xl border-2 border-slate-200">
            <table class="w-full text-left text-xs" data-datatable data-server-paginated>
                <thead class="bg-slate-100/90 border-b-2 border-slate-200 text-slate-600 font-extrabold uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Waktu & IP</th>
                        <th class="py-3 px-4">Pengguna</th>
                        <th class="py-3 px-4">Wilayah</th>
                        <th class="py-3 px-4">Jabatan & Sektor</th>
                        <th class="py-3 px-4 text-right">Perubahan Upah</th>
                        <th class="py-3 px-4">Mengapa Diperbarui? (Alasan)</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @foreach($logs as $log)
                        @php
                            $diff = $log->gaji_sebelumnya ? $log->gaji_nominal - $log->gaji_sebelumnya : null;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            
                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ $log->created_at->format('d M Y') }}</div>
                                <div class="text-[10px] text-slate-400 font-mono">{{ $log->created_at->format('H:i:s') }} WIB</div>
                                <div class="text-[9px] text-slate-400 mt-0.5"><i class="fa-solid fa-network-wired text-[8px] mr-1"></i>{{ $log->ip_address ?? '127.0.0.1' }}</div>
                            </td>

                            <td class="py-3 px-4">
                                <div class="font-extrabold text-slate-900">{{ $log->user ? $log->user->name : 'User #' . $log->user_id }}</div>
                                <div class="text-[11px] text-slate-500 font-mono">@<span>{{ $log->user ? $log->user->username : '-' }}</span></div>
                            </td>

                            <td class="py-3 px-4 whitespace-nowrap">
                                <div class="font-bold text-slate-800">{{ $log->provinsi ? $log->provinsi->nama_provinsi : '-' }}</div>
                                <span class="badge-pill bg-slate-100 text-slate-500 text-[9px] mt-0.5">Tahun {{ $log->tahun }}</span>
                            </td>

                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900">{{ $log->jabatan }}</div>
                                <div class="text-[11px] text-slate-500">{{ $log->bidang_kerja }}</div>
                                <span class="text-[10px] text-slate-400">({{ $log->status_pekerjaan ?? 'Tetap' }})</span>
                            </td>

                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                <div class="font-black text-emerald-700 text-sm">
                                    Rp {{ number_format($log->gaji_nominal, 0, ',', '.') }}
                                </div>
                                @if($log->gaji_sebelumnya)
                                    <div class="text-[10px] text-slate-400 line-through">
                                        Rp {{ number_format($log->gaji_sebelumnya, 0, ',', '.') }}
                                    </div>
                                    @if($diff !== null)
                                        <div class="text-[10px] font-bold {{ $diff >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                            {{ $diff >= 0 ? '+' : '' }}Rp {{ number_format($diff, 0, ',', '.') }}
                                        </div>
                                    @endif
                                @else
                                    <span class="badge-pill bg-sky-50 text-sky-700 border border-sky-200 text-[9px]">Entri Pertama</span>
                                @endif
                            </td>

                            <td class="py-3 px-4 max-w-sm">
                                <div class="bg-amber-50/70 border border-amber-200 text-amber-950 p-2 rounded-xl text-xs leading-relaxed">
                                    <i class="fa-solid fa-quote-left text-[10px] text-amber-400 mr-1"></i>
                                    {{ $log->alasan_pembaruan }}
                                </div>
                            </td>

                            <td class="py-3 px-4 text-center whitespace-nowrap">
                                <form action="{{ route('admin.wage-logs.destroy', $log->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus log pembaruan upah (#{{ $log->id }}) milik {{ addslashes($log->user ? $log->user->name : 'User') }}? Tindakan ini tidak dapat dibatalkan.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-xs font-bold transition flex items-center gap-1 mx-auto"
                                            title="Hapus Log Pembaruan">
                                        <i class="fa-solid fa-trash-can text-[10px]"></i>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pt-2">
            {{ $logs->links() }}
        </div>
    </section>

    <section class="card-tactile p-6 bg-white space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-black text-slate-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-id-badge text-emerald-600"></i>
                    Direktori Akun Pengguna Terdaftar ({{ $users->count() }} Akun)
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftar akun pengguna resmi beserta status profil dan total log yang telah dikontribusikan.</p>
            </div>
            <span class="badge-pill bg-slate-100 text-slate-700 text-xs">Basis Data Pengguna</span>
        </div>

        <div class="overflow-x-auto rounded-2xl border-2 border-slate-200">
            <table class="w-full text-left text-xs" data-datatable>
                <thead class="bg-slate-100/90 border-b-2 border-slate-200 text-slate-600 font-extrabold uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">ID</th>
                        <th class="py-3 px-4">Nama & Username</th>
                        <th class="py-3 px-4">Peran</th>
                        <th class="py-3 px-4">Provinsi Kerja</th>
                        <th class="py-3 px-4">Sektor & Jabatan</th>
                        <th class="py-3 px-4 text-right">Upah Pokok Terkini</th>
                        <th class="py-3 px-4 text-center">Total Log</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                    @foreach($users as $u)
                        <tr class="hover:bg-slate-50/80 transition-colors {{ $u->isAdmin() ? 'bg-amber-50/30' : '' }}">
                            <td class="py-3 px-4 text-center font-bold text-slate-400">#{{ $u->id }}</td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                    <span>{{ $u->name }}</span>
                                    @if($u->isAdmin())
                                        <i class="fa-solid fa-shield-halved text-amber-500 text-[10px]" title="Administrator"></i>
                                    @endif
                                </div>
                                <div class="text-[11px] text-slate-400 font-mono">@<span>{{ $u->username }}</span> &bull; {{ $u->email }}</div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="badge-pill {{ $u->isAdmin() ? 'bg-amber-100 text-amber-800 border border-amber-300' : 'bg-slate-100 text-slate-700' }} text-[10px]">
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-800">
                                {{ $u->provinsi ? $u->provinsi->nama_provinsi : '-' }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-bold text-slate-900">{{ $u->jabatan ?? '-' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $u->bidang_kerja ?? '-' }}</div>
                            </td>
                            <td class="py-3 px-4 text-right font-black text-emerald-700 text-sm">
                                {{ $u->gaji_pokok ? 'Rp ' . number_format($u->gaji_pokok, 0, ',', '.') : '-' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="badge-pill bg-slate-100 text-slate-700 font-bold text-[10px]">
                                    {{ $u->wage_logs_count }} log
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
