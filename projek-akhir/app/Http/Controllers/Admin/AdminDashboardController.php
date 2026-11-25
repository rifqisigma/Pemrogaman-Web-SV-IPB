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
        // 1. National Statistics
        $totalUsers = User::where('role', 'user')->count();
        $totalLogs = WageLog::count();
        $avgWorkerSalary = WageLog::avg('gaji_nominal') ?? 0;
        
        $latestYear = Ump::max('tahun') ?? 2026;
        $nationalAvgUmp = Ump::where('tahun', $latestYear)->avg('nominal') ?? 0;
        $totalProvinces = Provinsi::count();

        // 2. Logs with Filters
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

        // 3. User Directory (Users & their latest wage status)
        $usersList = User::with('provinsi')
            ->withCount('wageLogs')
            ->orderBy('role', 'asc') // admin first
            ->orderBy('id', 'asc')
            ->get();

        // 4. Sektor aggregation for admin analytics chart
        $sectorBreakdown = WageLog::select('bidang_kerja', DB::raw('AVG(gaji_nominal) as avg_nominal'), DB::raw('COUNT(*) as total'))
            ->groupBy('bidang_kerja')
            ->orderBy('avg_nominal', 'desc')
            ->get();

        $provinsis = Provinsi::orderBy('nama_provinsi')->get();
        $years = WageLog::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('admin.index', compact(
            'totalUsers',
            'totalLogs',
            'avgWorkerSalary',
            'nationalAvgUmp',
            'latestYear',
            'totalProvinces',
            'logs',
            'usersList',
            'sectorBreakdown',
            'provinsis',
            'years'
        ));
    }

    public function destroyLog(WageLog $log)
    {
        $logId = $log->id;
        $userName = $log->user ? $log->user->name : "User #{$log->user_id}";

        $log->delete();

        return redirect()->route('admin.index')
            ->with('success', "Log riwayat pembaruan upah (#{$logId}) milik {$userName} berhasil dihapus dari sistem audit.");
    }
}
