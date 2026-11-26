<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'National Wage') &bull; Pusat Analisis Upah & UMP Indonesia</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                        },
                        accent: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                        },
                        tactile: {
                            border: '#cbd5e1',
                            shadow: '#94a3b8',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
        }

        .btn-tactile-primary {
            background-color: #16a34a;
            color: #ffffff;
            border-bottom: 4px solid #15803d;
            border-radius: 1rem;
            font-weight: 700;
            transition: all 0.1s ease-in-out;
        }
        .btn-tactile-primary:hover {
            background-color: #15803d;
        }
        .btn-tactile-primary:active {
            transform: translateY(2px);
            border-bottom-width: 2px;
        }

        .btn-tactile-secondary {
            background-color: #ffffff;
            color: #334155;
            border: 2px solid #e2e8f0;
            border-bottom: 4px solid #cbd5e1;
            border-radius: 1rem;
            font-weight: 700;
            transition: all 0.1s ease-in-out;
        }
        .btn-tactile-secondary:hover {
            background-color: #f8fafc;
            border-color: #94a3b8;
        }
        .btn-tactile-secondary:active {
            transform: translateY(2px);
            border-bottom-width: 2px;
        }

        .btn-tactile-accent {
            background-color: #0284c7;
            color: #ffffff;
            border-bottom: 4px solid #0369a1;
            border-radius: 1rem;
            font-weight: 700;
            transition: all 0.1s ease-in-out;
        }
        .btn-tactile-accent:hover {
            background-color: #0369a1;
        }
        .btn-tactile-accent:active {
            transform: translateY(2px);
            border-bottom-width: 2px;
        }

        .card-tactile {
            background: #ffffff;
            border: 2px solid #e2e8f0;
            border-bottom: 4px solid #e2e8f0;
            border-radius: 1.25rem;
            transition: transform 0.15s ease, border-color 0.15s ease;
        }
        .card-tactile:hover {
            border-color: #cbd5e1;
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.025em;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.4rem 0.85rem;
            background-color: #f8fafc;
            font-size: 0.875rem;
            margin-left: 0.5rem;
            outline: none;
            transition: all 0.15s ease;
        }
        .dataTables_wrapper .dataTables_filter input:focus {
            background-color: #ffffff;
            border-color: #16a34a;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 2px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.35rem 0.65rem;
            background-color: #f8fafc;
            font-weight: 700;
            color: #334155;
            outline: none;
        }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #475569;
        }
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            margin-top: 1rem;
            font-size: 0.8125rem;
            font-weight: 700;
            color: #475569;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 0.75rem !important;
            border: 2px solid #e2e8f0 !important;
            background: #ffffff !important;
            color: #334155 !important;
            font-weight: 700 !important;
            padding: 0.3rem 0.75rem !important;
            margin-left: 0.25rem !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #16a34a !important;
            border-color: #15803d !important;
            color: #ffffff !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.45;
            pointer-events: none;
        }
        table.dataTable.no-footer {
            border-bottom: 2px solid #e2e8f0;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-full flex flex-col antialiased selection:bg-emerald-100 selection:text-emerald-900">

    <header class="bg-white/95 backdrop-blur-md sticky top-0 z-40 border-b-2 border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500 border-b-4 border-emerald-700 flex items-center justify-center text-white shadow-sm group-hover:scale-105 transition-transform">
                            <i class="fa-solid fa-scale-balanced text-xl"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xl font-extrabold text-slate-900 tracking-tight">National<span class="text-emerald-600">Wage</span></span>
                                <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-300">INDONESIA</span>
                            </div>
                            <p class="text-xs text-slate-500 font-medium">Transparansi Upah & Analisis UMP</p>
                        </div>
                    </a>

                    <nav class="hidden md:flex items-center gap-1">
                        <a href="{{ route('home') }}" 
                           class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-700 border-2 border-emerald-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i class="fa-solid fa-chart-pie mr-1.5 text-xs"></i> Beranda
                        </a>
                        <a href="{{ route('dashboard') }}" 
                           class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('dashboard') ? 'bg-emerald-50 text-emerald-700 border-2 border-emerald-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i class="fa-solid fa-chart-line mr-1.5 text-xs"></i> Dashboard
                        </a>
                        <a href="{{ route('datasheet.index') }}" 
                           class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('datasheet.*') ? 'bg-emerald-50 text-emerald-700 border-2 border-emerald-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i class="fa-solid fa-table-list mr-1.5 text-xs"></i> Datasheet
                        </a>
                        <a href="{{ route('scientific-report.index') }}" 
                           class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('scientific-report.*') ? 'bg-emerald-50 text-emerald-700 border-2 border-emerald-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i class="fa-solid fa-book-bookmark mr-1.5 text-xs"></i> Scientific Report
                        </a>
                        <a href="{{ route('service-api.index') }}" 
                           class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('service-api.*') ? 'bg-emerald-50 text-emerald-700 border-2 border-emerald-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                            <i class="fa-solid fa-bolt mr-1.5 text-xs text-sky-600"></i> Service API
                        </a>

                        @auth
                            <a href="{{ route('user.index') }}" 
                               class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('user.*') ? 'bg-emerald-50 text-emerald-700 border-2 border-emerald-200' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                                <i class="fa-solid fa-user-circle mr-1.5 text-xs"></i> Profil & Upah
                            </a>
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.index') }}" 
                                   class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ request()->routeIs('admin.*') ? 'bg-amber-50 text-amber-800 border-2 border-amber-300' : 'text-amber-700 hover:bg-amber-50' }}">
                                    <i class="fa-solid fa-shield-halved mr-1.5 text-xs"></i> Panel Admin
                                </a>
                            @endif
                        @endauth
                    </nav>
                </div>

                <div class="flex items-center gap-3">
                    @guest
                        <a href="{{ route('login') }}" class="btn-tactile-secondary px-4 py-2 text-sm inline-flex items-center gap-1.5">
                            <i class="fa-solid fa-arrow-right-to-bracket text-xs text-slate-500"></i>
                            <span>Masuk</span>
                        </a>
                        <a href="{{ route('register') }}" class="btn-tactile-primary px-5 py-2 text-sm inline-flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-user-plus text-xs"></i>
                            <span>Daftar</span>
                        </a>
                    @else
                        <div class="hidden sm:flex items-center gap-3 bg-slate-50 border-2 border-slate-200 rounded-2xl px-3 py-1.5">
                            <div class="w-8 h-8 rounded-xl bg-slate-200 text-slate-700 flex items-center justify-center font-bold text-xs uppercase">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                            <div class="text-left text-xs leading-tight">
                                <div class="font-bold text-slate-800 truncate max-w-[130px]">{{ Auth::user()->name }}</div>
                                <div class="text-slate-500 font-medium">
                                    @if(Auth::user()->isAdmin())
                                        <span class="text-amber-600 font-bold">Admin</span>
                                    @else
                                        <span>{{ Auth::user()->provinsi ? Auth::user()->provinsi->nama_provinsi : 'Pekerja' }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <button type="button" 
                                onclick="openLogoutModal()" 
                                class="btn-tactile-secondary px-3.5 py-2 text-xs text-rose-600 hover:text-rose-700 hover:border-rose-300"
                                title="Keluar / Logout">
                            <i class="fa-solid fa-power-off"></i>
                            <span class="hidden sm:inline ml-1">Keluar</span>
                        </button>
                    @endguest
                </div>

            </div>
        </div>

        <div class="md:hidden border-t border-slate-200 bg-white px-4 py-2 flex items-center justify-around text-xs font-bold">
            <a href="{{ route('home') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('home') ? 'text-emerald-600 font-extrabold' : 'text-slate-600' }}">
                <i class="fa-solid fa-chart-pie text-base mb-0.5"></i>
                <span>Beranda</span>
            </a>
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('dashboard') ? 'text-emerald-600 font-extrabold' : 'text-slate-600' }}">
                <i class="fa-solid fa-chart-line text-base mb-0.5"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('datasheet.index') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('datasheet.*') ? 'text-emerald-600 font-extrabold' : 'text-slate-600' }}">
                <i class="fa-solid fa-table-list text-base mb-0.5"></i>
                <span>Datasheet</span>
            </a>
            <a href="{{ route('scientific-report.index') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('scientific-report.*') ? 'text-emerald-600 font-extrabold' : 'text-slate-600' }}">
                <i class="fa-solid fa-book-bookmark text-base mb-0.5"></i>
                <span>Laporan</span>
            </a>
            <a href="{{ route('service-api.index') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('service-api.*') ? 'text-sky-600 font-extrabold' : 'text-slate-600' }}">
                <i class="fa-solid fa-bolt text-base mb-0.5 text-sky-600"></i>
                <span>API</span>
            </a>
            @auth
                <a href="{{ route('user.index') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('user.*') ? 'text-emerald-600 font-extrabold' : 'text-slate-600' }}">
                    <i class="fa-solid fa-user-circle text-base mb-0.5"></i>
                    <span>Profil</span>
                </a>
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.index') }}" class="flex flex-col items-center py-1 {{ request()->routeIs('admin.*') ? 'text-amber-600 font-extrabold' : 'text-slate-600' }}">
                        <i class="fa-solid fa-shield-halved text-base mb-0.5"></i>
                        <span>Admin</span>
                    </a>
                @endif
            @endauth
        </div>
    </header>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 w-full">
        @if(session('success'))
            <div class="card-tactile bg-emerald-50 border-emerald-300 text-emerald-900 p-4 mb-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-check text-sm"></i>
                    </div>
                    <div class="text-sm font-semibold">{{ session('success') }}</div>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('warning'))
            <div class="card-tactile bg-amber-50 border-amber-300 text-amber-900 p-4 mb-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                    </div>
                    <div class="text-sm font-semibold">{{ session('warning') }}</div>
                </div>
                <button onclick="this.parentElement.remove()" class="text-amber-700 hover:text-amber-900 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="card-tactile bg-rose-50 border-rose-300 text-rose-900 p-4 mb-4 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-rose-500 text-white flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-sm"></i>
                    </div>
                    <div class="text-sm font-semibold">{{ session('error') }}</div>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900 p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if($errors->any())
            <div class="card-tactile bg-rose-50 border-rose-300 text-rose-900 p-4 mb-4 shadow-sm">
                <div class="flex items-center gap-2 font-bold mb-1">
                    <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                    <span>Terdapat beberapa kesalahan:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-1 text-rose-800 ml-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <footer class="bg-white border-t-2 border-slate-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <span class="text-lg font-extrabold text-slate-900">National<span class="text-emerald-600">Wage</span></span>
                    </div>
                    <p class="text-sm text-slate-600 leading-relaxed max-w-md">
                        Platform keterbukaan data dan analisis komparatif Upah Minimum Provinsi (UMP) serta kondisi upah pekerja di berbagai sektor industri di seluruh Indonesia.
                    </p>
                    <div class="mt-4 flex items-center gap-2">
                        <span class="badge-pill bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <i class="fa-solid fa-lock text-[10px] mr-1"></i> Data Terbuka
                        </span>
                        <span class="badge-pill bg-sky-50 text-sky-700 border border-sky-200">
                            <i class="fa-solid fa-shield text-[10px] mr-1"></i> Berbasis Bukti
                        </span>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold text-slate-800 text-sm mb-3">Navigasi Utama</h4>
                    <ul class="space-y-2 text-sm text-slate-600">
                        <li><a href="{{ route('home') }}" class="hover:text-emerald-600 transition">Peta & Beranda Nasional</a></li>
                        <li><a href="{{ route('dashboard') }}" class="hover:text-emerald-600 transition">Dashboard Statistik UMP</a></li>
                        <li><a href="{{ route('datasheet.index') }}" class="hover:text-emerald-600 transition">Ekspor Datasheet Excel</a></li>
                        <li><a href="{{ route('scientific-report.index') }}" class="hover:text-emerald-600 transition">Laporan Ilmiah & Sitasi</a></li>
                        <li><a href="{{ route('service-api.index') }}" class="hover:text-emerald-600 transition">Service API (REST Fleksibel)</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-slate-800 text-sm mb-3">Akses & Keamanan</h4>
                    <ul class="space-y-2 text-sm text-slate-600">
                        @guest
                            <li><a href="{{ route('login') }}" class="hover:text-emerald-600 transition">Masuk Akun</a></li>
                            <li><a href="{{ route('register') }}" class="hover:text-emerald-600 transition">Daftar Akun Pekerja</a></li>
                        @else
                            <li><a href="{{ route('user.index') }}" class="hover:text-emerald-600 transition">Profil Pengguna</a></li>
                            @if(Auth::user()->isAdmin())
                                <li><a href="{{ route('admin.index') }}" class="hover:text-amber-600 transition">Admin Audit Log</a></li>
                            @endif
                        @endguest
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-100 mt-8 pt-6 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} National Wage Project. Didedikasikan untuk riset & transparansi tenaga kerja Indonesia.</p>
                <p class="mt-2 sm:mt-0 font-medium">Dirancang dengan filosofi UI ramah & terstruktur.</p>
            </div>
        </div>
    </footer>

    @auth
    <div id="logoutModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
        <div class="card-tactile w-full max-w-md p-6 bg-white shadow-2xl transform transition-all">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50 border-2 border-rose-200 text-rose-600 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-right-from-bracket text-lg"></i>
                </div>
                <div class="flex-1">
                    <span class="badge-pill bg-rose-50 text-rose-700 border border-rose-200 mb-1.5">KONFIRMASI KELUAR</span>
                    <h3 class="text-lg font-extrabold text-slate-900">Keluar dari Akun?</h3>
                    <p class="text-sm text-slate-500 mt-1 leading-relaxed">
                        Sesi login Anda akan diakhiri. Anda perlu masuk kembali untuk mengakses halaman profil maupun panel administrator.
                    </p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <button type="button" 
                        onclick="closeLogoutModal()" 
                        class="btn-tactile-secondary px-4 py-2.5 text-sm">
                    Batal
                </button>

                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="bg-rose-600 hover:bg-rose-700 text-white border-b-4 border-rose-800 active:translate-y-0.5 active:border-b-2 rounded-2xl font-bold px-5 py-2.5 text-sm transition-all inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-power-off text-xs"></i>
                        <span>Ya, Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('logoutModal')?.addEventListener('click', function (e) {
            if (e.target === this) {
                closeLogoutModal();
            }
        });
    </script>
    @endauth

    <script>
        if (typeof $.fn.dataTable !== 'undefined') {
            $.extend(true, $.fn.dataTable.defaults, {
                pageLength: 10,
                lengthMenu: [[10, 20, 38, 50, 100, -1], [10, 20, 38, 50, 100, "Semua"]],
                language: {
                    search: "Cari:",
                    searchPlaceholder: "Ketik kata kunci...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    zeroRecords: "Data tidak ditemukan",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        }

        window.AppDataTable = {
            formatRupiah: function (val) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(val || 0);
            },

            registerYearRangeFilter: function (tableId, minSelector, maxSelector, colIdx) {
                $.fn.dataTable.ext.search.push(function (settings, data) {
                    if (settings.nTable.id !== tableId) return true;

                    const min = parseInt($(minSelector).val(), 10);
                    const max = parseInt($(maxSelector).val(), 10);
                    
                    const text = String(data[colIdx]).replace(/<[^>]*>?/gm, '').trim();
                    const yr = parseInt(text, 10) || 0;

                    if (
                        (isNaN(min) && isNaN(max)) ||
                        (isNaN(min) && yr <= max) ||
                        (min <= yr && isNaN(max)) ||
                        (min <= yr && yr <= max)
                    ) {
                        return true;
                    }
                    return false;
                });
            }
        };
    </script>

    @stack('scripts')
</body>
</html>
