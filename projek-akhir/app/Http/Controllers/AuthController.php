<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\User;
use App\Models\WageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.index')
                : redirect()->route('user.index');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'Username atau email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $loginField = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $remember = (bool) $request->boolean('remember');

        if (Auth::attempt([$loginField => $credentials['login'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $roleLabel = $user->isAdmin() ? 'Administrator' : 'Pengguna';

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.index'))
                    ->with('success', "Selamat datang kembali, {$user->name} ({$roleLabel})!");
            }

            return redirect()->intended(route('user.index'))
                ->with('success', "Selamat datang di National Wage, {$user->name}!");
        }

        return back()->withErrors([
            'login' => 'Kredensial yang dimasukkan tidak cocok dengan catatan kami.',
        ])->onlyInput('login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.index')
                : redirect()->route('user.index');
        }

        $provinsis = Provinsi::orderBy('nama_provinsi')->get();

        return view('auth.register', compact('provinsis'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'provinsi_id' => ['nullable', 'exists:provinsis,id'],
            'bidang_kerja' => ['nullable', 'string', 'max:100'],
            'jabatan' => ['nullable', 'string', 'max:100'],
            'status_pekerjaan' => ['nullable', 'string', 'in:Tetap,Kontrak,Freelance,Magang,Lainnya'],
            'gaji_pokok' => ['nullable', 'numeric', 'min:0'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username ini sudah digunakan.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, garis hubung, dan garis bawah.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => strtolower($validated['username']),
            'email' => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => 'user',
            'provinsi_id' => $validated['provinsi_id'] ?? null,
            'bidang_kerja' => $validated['bidang_kerja'] ?? null,
            'jabatan' => $validated['jabatan'] ?? null,
            'status_pekerjaan' => $validated['status_pekerjaan'] ?? 'Tetap',
            'gaji_pokok' => $validated['gaji_pokok'] ?? null,
        ]);

        // If salary provided, create initial wage log
        if (!empty($validated['gaji_pokok']) && $validated['gaji_pokok'] > 0 && !empty($validated['provinsi_id'])) {
            WageLog::create([
                'user_id' => $user->id,
                'provinsi_id' => $user->provinsi_id,
                'bidang_kerja' => $user->bidang_kerja ?? 'Umum',
                'jabatan' => $user->jabatan ?? 'Staf',
                'status_pekerjaan' => $user->status_pekerjaan ?? 'Tetap',
                'gaji_nominal' => $validated['gaji_pokok'],
                'gaji_sebelumnya' => null,
                'alasan_pembaruan' => 'Pendaftaran pertama & input data upah awal akun',
                'tahun' => (int) date('Y'),
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('user.index')
            ->with('success', 'Akun Anda berhasil didaftarkan! Selamat bergabung di platform National Wage.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar (logout).');
    }
}
