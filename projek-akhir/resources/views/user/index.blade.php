@extends('layouts.app')

@section('title', 'Profil & Dashboard Upah Regional')

@section('content')
<div class="space-y-8">

    <section class="card-tactile p-6 bg-white flex flex-col md:flex-row md:items-center justify-between gap-5">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-emerald-100 border-2 border-emerald-300 text-emerald-800 flex items-center justify-center text-2xl font-black uppercase shadow-sm">
                {{ substr($user->name, 0, 2) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $user->name }}</h1>
                    <span class="badge-pill bg-slate-100 text-slate-700 text-xs font-mono">@<span>{{ $user->username }}</span></span>
                    <span class="badge-pill {{ $user->isAdmin() ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                        <i class="fa-solid {{ $user->isAdmin() ? 'fa-shield-halved' : 'fa-user-tie' }} mr-1 text-[10px]"></i>
                        {{ $user->isAdmin() ? 'Administrator' : 'Pekerja Aktif' }}
                    </span>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500 mt-1.5 font-medium">
                    <span><i class="fa-solid fa-envelope text-slate-400 mr-1"></i> {{ $user->email }}</span>
                    <span>&bull;</span>
                    <span><i class="fa-solid fa-location-dot text-emerald-500 mr-1"></i> {{ $prov ? $prov->nama_provinsi : 'Provinsi belum diatur' }}</span>
                    <span>&bull;</span>
                    <span><i class="fa-solid fa-briefcase text-slate-400 mr-1"></i> {{ $user->jabatan ?? 'Jabatan belum diisi' }} ({{ $user->bidang_kerja ?? '-' }})</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="#formUpdate" class="btn-tactile-primary px-4 py-2.5 text-xs inline-flex items-center gap-1.5 shadow-sm">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Perbarui Data Upah</span>
            </a>
        </div>
    </section>

    <section class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                    <i class="fa-solid fa-chart-column text-emerald-600"></i>
                    Dashboard Upah Regional: {{ $prov ? $prov->nama_provinsi : 'Semua Wilayah' }}
                </h2>
                <p class="text-xs text-slate-500">
                    Analisis komparatif gaji Anda terhadap ketetapan Upah Minimum Provinsi (UMP) dan dinamika historis wilayah Anda.
                </p>
            </div>
            @if($prov)
                <span class="badge-pill bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs">
                    Kode Provinsi: {{ $prov->kode_provinsi }}
                </span>
            @endif
        </div>

        @if($prov)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <div class="card-tactile p-5 bg-white flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Gaji Pokok Anda Saat Ini</span>
                        <div class="text-2xl font-black text-slate-900 mt-1">
                            {{ $user->gaji_pokok ? 'Rp ' . number_format($user->gaji_pokok, 0, ',', '.') : 'Belum diisi' }}
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            Status: <span class="font-bold text-slate-700">{{ $user->status_pekerjaan ?? 'Tetap' }}</span>
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-400">
                        Sektor: <span class="font-bold text-slate-600">{{ $user->bidang_kerja ?? '-' }}</span>
                    </div>
                </div>

                <div class="card-tactile p-5 bg-white flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">UMP {{ $prov->nama_provinsi }} ({{ $ump ? $ump->tahun : date('Y') }})</span>
                        <div class="text-2xl font-black text-emerald-700 mt-1">
                            {{ $ump ? 'Rp ' . number_format($ump->nominal, 0, ',', '.') : '-' }}
                        </div>
                        <div class="text-xs text-slate-500 mt-1">
                            Standar acuan resmi pemerintah provinsi
                        </div>
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-400">
                        Ketetapan UMP Regional {{ $ump ? $ump->tahun : '' }}
                    </div>
                </div>

                <div class="card-tactile p-5 bg-white flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Komparasi vs UMP</span>
                        @if(!is_null($diffPct))
                            <div class="text-2xl font-black {{ $diffPct >= 0 ? 'text-emerald-600' : 'text-rose-600' }} mt-1">
                                {{ $diffPct >= 0 ? '+' : '' }}{{ $diffPct }}%
                            </div>
                            <div class="text-xs font-bold {{ $diff >= 0 ? 'text-emerald-700' : 'text-rose-700' }} mt-1">
                                {{ $diff >= 0 ? 'Rp ' . number_format($diff, 0, ',', '.') . ' di atas UMP' : 'Rp ' . number_format(abs($diff), 0, ',', '.') . ' di bawah UMP' }}
                            </div>
                        @else
                            <div class="text-sm font-bold text-slate-400 mt-2">Isi gaji Anda untuk menghitung komparasi</div>
                        @endif
                    </div>
                    <div class="mt-4 pt-3 border-t border-slate-100 text-[11px] text-slate-400">
                        {{ $diffPct >= 0 ? 'Upah Anda memenuhi ketentuan di atas standar batas minimum.' : 'Peringatan: Berada di bawah standar batas minimum regional.' }}
                    </div>
                </div>

            </div>

            @if($history->isNotEmpty())
            <div class="card-tactile p-6 bg-white space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-black text-slate-900 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-chart-line text-emerald-600"></i>
                            Riwayat Kenaikan UMP Provinsi {{ $prov->nama_provinsi }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Grafik perkembangan upah minimum wilayah kerja Anda</p>
                    </div>
                    <span class="badge-pill bg-slate-100 text-slate-700 text-[10px]">Data Resmi</span>
                </div>
                <div class="h-56">
                    <canvas id="provincialChart"></canvas>
                </div>
            </div>
            @endif

        @else
            <div class="card-tactile p-6 bg-amber-50 border-amber-300 text-amber-900 flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-sm">Provinsi Belum Ditentukan</h4>
                    <p class="text-xs text-amber-800 mt-0.5">Silakan pilih provinsi tempat Anda bekerja pada formulir di bawah untuk membuka dashboard regional otomatis.</p>
                </div>
            </div>
        @endif
    </section>

    <section id="formUpdate" class="card-tactile p-6 sm:p-8 bg-white space-y-6">
        <div>
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                    <i class="fa-solid fa-file-pen text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-slate-900">Formulir Pembaruan Data Pekerja & Upah Kerja</h3>
                    <p class="text-xs text-slate-500">Setiap perubahan upah akan dicatat dalam audit trail demi akuntabilitas data.</p>
                </div>
            </div>
        </div>

        <form action="{{ route('user.update-wage') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Nama Lengkap *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="provinsi_id" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Provinsi Tempat Kerja *</label>
                    <select name="provinsi_id" id="provinsi_id" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none">
                        <option value="">-- Pilih Provinsi Bekerja --</option>
                        @foreach($provinces as $p)
                            <option value="{{ $p->id }}" {{ old('provinsi_id', $user->provinsi_id) == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_provinsi }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="bidang_kerja" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Sektor / Bidang Kerja *</label>
                    <input type="text" name="bidang_kerja" id="bidang_kerja" value="{{ old('bidang_kerja', $user->bidang_kerja) }}" required
                           placeholder="Teknologi, Manufaktur, Ritel"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="jabatan" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Jabatan / Posisi *</label>
                    <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan', $user->jabatan) }}" required
                           placeholder="Software Engineer, Staf QC"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none">
                </div>

                <div>
                    <label for="status_pekerjaan" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Status Karyawan *</label>
                    <select name="status_pekerjaan" id="status_pekerjaan" required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none">
                        <option value="Tetap" {{ old('status_pekerjaan', $user->status_pekerjaan) == 'Tetap' ? 'selected' : '' }}>Karyawan Tetap</option>
                        <option value="Kontrak" {{ old('status_pekerjaan', $user->status_pekerjaan) == 'Kontrak' ? 'selected' : '' }}>Karyawan Kontrak</option>
                        <option value="Freelance" {{ old('status_pekerjaan', $user->status_pekerjaan) == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                        <option value="Magang" {{ old('status_pekerjaan', $user->status_pekerjaan) == 'Magang' ? 'selected' : '' }}>Magang</option>
                        <option value="Lainnya" {{ old('status_pekerjaan', $user->status_pekerjaan) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="gaji_pokok" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Nominal Upah Kerja Baru (Rp/Bulan) *</label>
                <div class="relative max-w-md">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-xs font-bold text-slate-400">Rp</span>
                    <input type="number" name="gaji_pokok" id="gaji_pokok" value="{{ old('gaji_pokok', $user->gaji_pokok) }}" required min="100000" step="50000"
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:border-emerald-500 focus:bg-white focus:outline-none">
                </div>
            </div>

            <div>
                <label for="alasan_pembaruan" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">
                    Alasan Pembaruan Data Upah * <span class="text-[11px] text-slate-400 font-normal lowercase">(catatan untuk log audit sistem)</span>
                </label>
                <textarea name="alasan_pembaruan" id="alasan_pembaruan" rows="3" required
                          placeholder="Jelaskan mengapa Anda memperbarui data upah (contoh: Kenaikan gaji tahunan 2026, Penyesuaian UMP wilayah, Promosi jabatan ke level Senior, dll.)"
                          class="w-full px-3.5 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:border-emerald-500 focus:bg-white focus:outline-none">{{ old('alasan_pembaruan') }}</textarea>

                <div class="flex flex-wrap items-center gap-1.5 mt-2">
                    <span class="text-[11px] font-bold text-slate-400">Pilih Cepat:</span>
                    <button type="button" onclick="setReason('Kenaikan gaji tahunan & evaluasi kinerja')" class="px-2 py-0.5 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 rounded text-[11px] font-medium text-slate-600 transition">Kenaikan Tahunan</button>
                    <button type="button" onclick="setReason('Promosi jabatan dan peningkatan tanggung jawab')" class="px-2 py-0.5 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 rounded text-[11px] font-medium text-slate-600 transition">Promosi Jabatan</button>
                    <button type="button" onclick="setReason('Penyesuaian kenaikan UMP daerah setempat')" class="px-2 py-0.5 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 rounded text-[11px] font-medium text-slate-600 transition">Penyesuaian UMP</button>
                    <button type="button" onclick="setReason('Pindah tempat kerja / perusahaan baru')" class="px-2 py-0.5 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 rounded text-[11px] font-medium text-slate-600 transition">Pindah Perusahaan</button>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-tactile-primary px-6 py-3 text-sm inline-flex items-center gap-2">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span>Simpan & Perbarui Riwayat Upah</span>
                </button>
            </div>
        </form>
    </section>

    <section class="card-tactile p-6 bg-white space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="font-black text-slate-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-emerald-600"></i>
                    Riwayat Log Pembaruan Upah Anda
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Catatan jejak waktu (audit log) perubahan data kerja Anda</p>
            </div>
            <span class="badge-pill bg-slate-100 text-slate-700 text-xs">{{ $logs->count() }} Riwayat</span>
        </div>

        @if($logs->isEmpty())
            <div class="p-8 text-center text-slate-400 bg-slate-50 rounded-xl border border-slate-200">
                <i class="fa-regular fa-folder-open text-3xl mb-2 text-slate-300"></i>
                <p class="text-xs font-semibold">Belum ada catatan riwayat pembaruan upah.</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-2xl border-2 border-slate-200">
                <table class="w-full text-left text-xs" data-datatable>
                    <thead class="bg-slate-100/80 border-b-2 border-slate-200 text-slate-600 font-extrabold uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4">Waktu Tercatat</th>
                            <th class="py-3 px-4">Provinsi</th>
                            <th class="py-3 px-4">Jabatan & Sektor</th>
                            <th class="py-3 px-4 text-right">Nominal Lama</th>
                            <th class="py-3 px-4 text-right">Nominal Baru</th>
                            <th class="py-3 px-4">Alasan Pembaruan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @foreach($logs as $log)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                                    <div class="font-bold text-slate-800">{{ $log->created_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $log->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="py-3 px-4 font-bold text-slate-800">
                                    {{ $log->provinsi ? $log->provinsi->nama_provinsi : '-' }}
                                </td>
                                <td class="py-3 px-4">
                                    <div class="font-bold text-slate-900">{{ $log->jabatan }}</div>
                                    <div class="text-[11px] text-slate-500">{{ $log->bidang_kerja }} ({{ $log->status_pekerjaan }})</div>
                                </td>
                                <td class="py-3 px-4 text-right text-slate-400">
                                    {{ $log->gaji_sebelumnya ? 'Rp ' . number_format($log->gaji_sebelumnya, 0, ',', '.') : '-' }}
                                </td>
                                <td class="py-3 px-4 text-right font-black text-emerald-700 text-sm">
                                    Rp {{ number_format($log->gaji_nominal, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4 max-w-xs text-slate-600">
                                    <span class="bg-slate-100 px-2 py-1 rounded text-[11px] inline-block">
                                        {{ $log->alasan_pembaruan }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

</div>

@push('scripts')
<script>
    function setReason(text) {
        document.getElementById('alasan_pembaruan').value = text;
    }

    @if($history->isNotEmpty())
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('provincialChart');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');
            const raw = @json($history);
            const data = Array.isArray(raw) ? raw : Object.values(raw);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.map(d => d.tahun),
                    datasets: [{
                        label: 'UMP (Rp)',
                        data: data.map(d => Number(d.nominal)),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.12)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#059669',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(c) {
                                    return 'UMP: Rp ' + Number(c.raw).toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            ticks: {
                                callback: function(v) {
                                    return 'Rp ' + (v / 1000000).toFixed(1) + ' Jt';
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
        });
    @endif
</script>
@endpush
@endsection
