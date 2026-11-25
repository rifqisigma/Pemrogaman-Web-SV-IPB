@extends('layouts.app')

@section('title', 'Masuk Akun')

@section('content')
<div class="max-w-md mx-auto py-8">
    <div class="card-tactile p-6 sm:p-8 bg-white shadow-sm">
        
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="w-14 h-14 rounded-2xl bg-emerald-100 border-2 border-emerald-300 text-emerald-700 flex items-center justify-center mx-auto mb-3 shadow-sm">
                <i class="fa-solid fa-arrow-right-to-bracket text-2xl"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Masuk ke Akun</h2>
            <p class="text-xs text-slate-500 mt-1">Gunakan akun Anda untuk mengunduh laporan & berkontribusi data upah</p>
        </div>

        <!-- Form -->
        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="login" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">
                    Username atau Email
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-user text-sm"></i>
                    </span>
                    <input type="text" 
                           name="login" 
                           id="login" 
                           value="{{ old('login') }}" 
                           required 
                           autocomplete="username"
                           placeholder="admin atau nama_pengguna"
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600">
                        Kata Sandi
                    </label>
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-key text-sm"></i>
                    </span>
                    <input type="password" 
                           name="password" 
                           id="password" 
                           required 
                           autocomplete="current-password"
                           placeholder="••••••••"
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="flex items-center justify-between text-xs pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 w-4 h-4">
                    <span class="text-slate-600 font-semibold">Ingat Saya</span>
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="btn-tactile-primary w-full py-3 text-sm flex items-center justify-center gap-2">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    <span>Masuk Sekarang</span>
                </button>
            </div>
        </form>

        <!-- Quick Fill Helper for Tester/Examiner -->
        <div class="mt-6 pt-5 border-t-2 border-slate-100">
            <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-2 text-center">Akun Uji Coba Cepat (1-Klik)</p>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" 
                        onclick="fillCredentials('admin', 'admin123')"
                        class="px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg text-left text-xs text-amber-900 transition flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-amber-600"></i>
                    <div>
                        <div class="font-extrabold text-[11px]">Admin</div>
                        <div class="text-[10px] text-amber-700">admin / admin123</div>
                    </div>
                </button>
                <button type="button" 
                        onclick="fillCredentials('budi_santoso', 'password123')"
                        class="px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg text-left text-xs text-emerald-900 transition flex items-center gap-2">
                    <i class="fa-solid fa-user-check text-emerald-600"></i>
                    <div>
                        <div class="font-extrabold text-[11px]">Pekerja (User)</div>
                        <div class="text-[10px] text-emerald-700">budi_santoso</div>
                    </div>
                </button>
            </div>
        </div>

        <!-- Register Link -->
        <div class="mt-5 text-center text-xs text-slate-500">
            Belum memiliki akun?
            <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:text-emerald-700 underline ml-1">
                Daftar Akun Baru
            </a>
        </div>

    </div>
</div>

<script>
    function fillCredentials(login, password) {
        document.getElementById('login').value = login;
        document.getElementById('password').value = password;
    }
</script>
@endsection
