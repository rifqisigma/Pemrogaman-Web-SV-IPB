@extends('layouts.app')

@section('title', 'Daftar Akun Baru')

@section('content')
<div class="max-w-xl mx-auto py-8">
    <div class="card-tactile p-6 sm:p-8 bg-white shadow-sm">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="w-14 h-14 rounded-2xl bg-emerald-100 border-2 border-emerald-300 text-emerald-700 flex items-center justify-center mx-auto mb-3 shadow-sm">
                <i class="fa-solid fa-user-plus text-2xl"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Daftar Akun National Wage</h2>
            <p class="text-xs text-slate-500 mt-1">Bergabunglah untuk melihat analitik upah regional & mengunduh laporan ilmiah</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Informasi Akun -->
            <div class="border-b-2 border-slate-100 pb-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-emerald-700 mb-3 flex items-center gap-1.5">
                    <i class="fa-solid fa-id-card"></i> 1. Kredensial Akun
                </h3>
                
                <div class="space-y-3">
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               placeholder="Contoh: Rian Anggara"
                               class="w-full px-3.5 py-2 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="username" class="block text-xs font-bold text-slate-700 mb-1">Username *</label>
                            <input type="text" name="username" id="username" value="{{ old('username') }}" required
                                   placeholder="rian_anggara"
                                   class="w-full px-3.5 py-2 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 mb-1">Email *</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                   placeholder="rian@example.com"
                                   class="w-full px-3.5 py-2 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi *</label>
                            <input type="password" name="password" id="password" required
                                   placeholder="Minimal 6 karakter"
                                   class="w-full px-3.5 py-2 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Sandi *</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                   placeholder="Ulangi kata sandi"
                                   class="w-full px-3.5 py-2 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profil Pekerjaan & Wilayah -->
            <div class="pt-1">
                <h3 class="text-xs font-black uppercase tracking-wider text-emerald-700 mb-3 flex items-center gap-1.5">
                    <i class="fa-solid fa-briefcase"></i> 2. Data Pekerjaan & Wilayah Kerja
                </h3>

                <div class="space-y-3">
                    <div>
                        <label for="provinsi_id" class="block text-xs font-bold text-slate-700 mb-1">Provinsi Tempat Bekerja</label>
                        <select name="provinsi_id" id="provinsi_id"
                                class="w-full px-3.5 py-2 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                            <option value="">-- Pilih Provinsi Bekerja --</option>
                            @foreach($provinsis as $p)
                                <option value="{{ $p->id }}" {{ old('provinsi_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->nama_provinsi }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-500 mt-1">Digunakan untuk menghasilkan komparasi UMP regional di dashboard Anda.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="bidang_kerja" class="block text-xs font-bold text-slate-700 mb-1">Bidang / Sektor Kerja</label>
                            <input type="text" name="bidang_kerja" id="bidang_kerja" value="{{ old('bidang_kerja') }}"
                                   placeholder="Teknologi, Manufaktur, Kesehatan"
                                   class="w-full px-3.5 py-2 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label for="jabatan" class="block text-xs font-bold text-slate-700 mb-1">Jabatan / Posisi</label>
                            <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan') }}"
                                   placeholder="Staff, Supervisor, Drafter"
                                   class="w-full px-3.5 py-2 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="status_pekerjaan" class="block text-xs font-bold text-slate-700 mb-1">Status Karyawan</label>
                            <select name="status_pekerjaan" id="status_pekerjaan"
                                    class="w-full px-3.5 py-2 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                                <option value="Tetap" {{ old('status_pekerjaan') == 'Tetap' ? 'selected' : '' }}>Karyawan Tetap</option>
                                <option value="Kontrak" {{ old('status_pekerjaan') == 'Kontrak' ? 'selected' : '' }}>Karyawan Kontrak (PKWT)</option>
                                <option value="Freelance" {{ old('status_pekerjaan') == 'Freelance' ? 'selected' : '' }}>Pekerja Lepas (Freelance)</option>
                                <option value="Magang" {{ old('status_pekerjaan') == 'Magang' ? 'selected' : '' }}>Magang / Intern</option>
                                <option value="Lainnya" {{ old('status_pekerjaan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label for="gaji_pokok" class="block text-xs font-bold text-slate-700 mb-1">Upah / Gaji Pokok (Rp)</label>
                            <input type="number" name="gaji_pokok" id="gaji_pokok" value="{{ old('gaji_pokok') }}"
                                   placeholder="Contoh: 5500000"
                                   min="0" step="50000"
                                   class="w-full px-3.5 py-2 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4">
                <button type="submit" class="btn-tactile-primary w-full py-3 text-sm flex items-center justify-center gap-2 shadow-sm">
                    <i class="fa-solid fa-user-check"></i>
                    <span>Selesaikan Pendaftaran</span>
                </button>
            </div>
        </form>

        <div class="mt-5 text-center text-xs text-slate-500">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-700 underline ml-1">
                Masuk di sini
            </a>
        </div>

    </div>
</div>
@endsection
