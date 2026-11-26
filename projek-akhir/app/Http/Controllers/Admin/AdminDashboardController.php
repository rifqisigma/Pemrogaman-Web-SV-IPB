<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Provinsi;
use App\Models\Ump;
use App\Models\User;
use App\Models\WageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $usersCount = User::where('role', 'user')->count();
        $logsCount = WageLog::count();
        $workerAvg = WageLog::avg('gaji_nominal') ?? 0;
        
        $year = Ump::max('tahun') ?? 2026;
        $umpAvg = Ump::where('tahun', $year)->avg('nominal') ?? 0;
        $provCount = Provinsi::count();

        $query = WageLog::with(['user', 'provinsi'])->orderBy('created_at', 'desc');

        if ($request->filled('provinsi_id')) {
            $query->where('provinsi_id', $request->provinsi_id);
        }

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('username', 'like', "%{$search}%");
                })
                ->orWhere('bidang_kerja', 'like', "%{$search}%")
                ->orWhere('jabatan', 'like', "%{$search}%")
                ->orWhere('alasan_pembaruan', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(15)->withQueryString();

        $users = User::with('provinsi')
            ->withCount('wageLogs')
            ->orderBy('role', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $sectors = WageLog::select('bidang_kerja', DB::raw('AVG(gaji_nominal) as avg_nominal'), DB::raw('COUNT(*) as total'))
            ->groupBy('bidang_kerja')
            ->orderBy('avg_nominal', 'desc')
            ->get();

        $provinces = Provinsi::orderBy('nama_provinsi')->get();
        $years = WageLog::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('admin.index', compact(
            'usersCount',
            'logsCount',
            'workerAvg',
            'umpAvg',
            'year',
            'provCount',
            'logs',
            'users',
            'sectors',
            'provinces',
            'years'
        ));
    }

    public function destroyLog(WageLog $log)
    {
        $id = $log->id;
        $name = $log->user ? $log->user->name : "User #{$log->user_id}";

        $log->delete();

        return redirect()->route('admin.index')
            ->with('success', "Log riwayat pembaruan upah (#{$id}) milik {$name} berhasil dihapus dari sistem audit.");
    }
}
