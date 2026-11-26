<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Ump;
use App\Models\WageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $provinces = Provinsi::orderBy('nama_provinsi')->get();
        $logs = WageLog::where('user_id', $user->id)
            ->with('provinsi')
            ->latest()
            ->get();

        $prov = $user->provinsi;
        $ump = null;
        $diff = null;
        $diffPct = null;
        $history = collect();
        $peerAvg = null;

        if ($prov) {
            $year = Ump::where('provinsi_id', $prov->id)->max('tahun') ?? 2026;
            $ump = Ump::where('provinsi_id', $prov->id)
                ->where('tahun', $year)
                ->first();

            if ($ump && $user->gaji_pokok) {
                $diff = $user->gaji_pokok - $ump->nominal;
                $diffPct = round(($diff / $ump->nominal) * 100, 1);
            }

            $history = Ump::where('provinsi_id', $prov->id)
                ->orderBy('tahun', 'desc')
                ->limit(8)
                ->get()
                ->sortBy('tahun')
                ->values();

            if ($user->bidang_kerja) {
                $peerAvg = WageLog::where('provinsi_id', $prov->id)
                    ->where('bidang_kerja', $user->bidang_kerja)
                    ->avg('gaji_nominal');
            }
        }

        return view('user.index', compact(
            'user',
            'provinces',
            'logs',
            'prov',
            'ump',
            'diff',
            'diffPct',
            'history',
            'peerAvg'
        ));
    }

    public function updateWage(Request $request)
    {
        $user = Auth::user();

        $valid = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'provinsi_id' => ['required', 'exists:provinsis,id'],
            'bidang_kerja' => ['required', 'string', 'max:100'],
            'jabatan' => ['required', 'string', 'max:100'],
            'status_pekerjaan' => ['required', 'string', 'in:Tetap,Kontrak,Freelance,Magang,Lainnya'],
            'gaji_pokok' => ['required', 'numeric', 'min:100000'],
            'alasan_pembaruan' => ['required', 'string', 'min:5', 'max:500'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'provinsi_id.required' => 'Silakan pilih provinsi tempat Anda bekerja.',
            'bidang_kerja.required' => 'Sektor/bidang kerja wajib diisi.',
            'jabatan.required' => 'Jabatan atau posisi wajib diisi.',
            'status_pekerjaan.required' => 'Status pekerjaan wajib dipilih.',
            'gaji_pokok.required' => 'Besaran nominal upah wajib diisi.',
            'gaji_pokok.min' => 'Nominal upah minimal Rp 100.000.',
            'alasan_pembaruan.required' => 'Alasan pembaruan data upah wajib dijelaskan.',
            'alasan_pembaruan.min' => 'Alasan pembaruan minimal 5 karakter.',
        ]);

        $prevGaji = $user->gaji_pokok;

        $user->update([
            'name' => $valid['name'],
            'provinsi_id' => $valid['provinsi_id'],
            'bidang_kerja' => $valid['bidang_kerja'],
            'jabatan' => $valid['jabatan'],
            'status_pekerjaan' => $valid['status_pekerjaan'],
            'gaji_pokok' => $valid['gaji_pokok'],
        ]);

        WageLog::create([
            'user_id' => $user->id,
            'provinsi_id' => $valid['provinsi_id'],
            'bidang_kerja' => $valid['bidang_kerja'],
            'jabatan' => $valid['jabatan'],
            'status_pekerjaan' => $valid['status_pekerjaan'],
            'gaji_nominal' => $valid['gaji_pokok'],
            'gaji_sebelumnya' => $prevGaji,
            'alasan_pembaruan' => $valid['alasan_pembaruan'],
            'tahun' => (int) date('Y'),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('user.index')
            ->with('success', 'Data profil & riwayat upah kerja Anda berhasil diperbarui dan dicatat dalam audit trail!');
    }
}
