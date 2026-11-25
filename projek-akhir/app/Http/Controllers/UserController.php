<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use App\Models\Ump;
use App\Models\WageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $provinsis = Provinsi::orderBy('nama_provinsi')->get();

        // User's personal logs
        $myLogs = WageLog::where('user_id', $user->id)
            ->with('provinsi')
            ->orderBy('created_at', 'desc')
            ->get();

        // Regional Dashboard Data based on user's province
        $userProvinsi = $user->provinsi;
        $latestUmp = null;
        $umpDifference = null;
        $umpPercentDiff = null;
        $provincialHistorical = collect();
        $sectorPeersAvg = null;

        if ($userProvinsi) {
            $latestYear = Ump::where('provinsi_id', $userProvinsi->id)->max('tahun') ?? 2026;
            $latestUmp = Ump::where('provinsi_id', $userProvinsi->id)
                ->where('tahun', $latestYear)
                ->first();

            if ($latestUmp && $user->gaji_pokok) {
                $umpDifference = $user->gaji_pokok - $latestUmp->nominal;
                $umpPercentDiff = round(($umpDifference / $latestUmp->nominal) * 100, 1);
            }

            // Historical UMP for this province
            $provincialHistorical = Ump::where('provinsi_id', $userProvinsi->id)
                ->orderBy('tahun', 'desc')
                ->limit(8)
                ->get()
                ->sortBy('tahun')
                ->values();

            // Peer average in same province or same sector
            if ($user->bidang_kerja) {
                $sectorPeersAvg = WageLog::where('provinsi_id', $userProvinsi->id)
                    ->where('bidang_kerja', $user->bidang_kerja)
                    ->avg('gaji_nominal');
            }
        }

        return view('user.index', compact(
            'user',
            'provinsis',
            'myLogs',
            'userProvinsi',
            'latestUmp',
            'umpDifference',
            'umpPercentDiff',
            'provincialHistorical',
            'sectorPeersAvg'
        ));
    }

    public function updateWage(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
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

        $gajiSebelumnya = $user->gaji_pokok;

        // 1. Update User Profile
        $user->update([
            'name' => $validated['name'],
            'provinsi_id' => $validated['provinsi_id'],
            'bidang_kerja' => $validated['bidang_kerja'],
            'jabatan' => $validated['jabatan'],
            'status_pekerjaan' => $validated['status_pekerjaan'],
            'gaji_pokok' => $validated['gaji_pokok'],
        ]);

        // 2. Insert into WageLog (Audit history)
        WageLog::create([
            'user_id' => $user->id,
            'provinsi_id' => $validated['provinsi_id'],
            'bidang_kerja' => $validated['bidang_kerja'],
            'jabatan' => $validated['jabatan'],
            'status_pekerjaan' => $validated['status_pekerjaan'],
            'gaji_nominal' => $validated['gaji_pokok'],
            'gaji_sebelumnya' => $gajiSebelumnya,
            'alasan_pembaruan' => $validated['alasan_pembaruan'],
            'tahun' => (int) date('Y'),
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('user.index')
            ->with('success', 'Data profil & riwayat upah kerja Anda berhasil diperbarui dan dicatat dalam audit trail!');
    }
}
