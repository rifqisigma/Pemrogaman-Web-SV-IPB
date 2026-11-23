<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Provinsi;
use App\Models\Ump;
use Illuminate\Http\Request;

class UmpStatistikController extends Controller
{
    /**
     * GET /api/ump/statistik?tahun=
     */
    public function statistik(Request $request)
    {
        $tahunTersedia = Ump::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $tahun = $request->input('tahun', $tahunTersedia->first() ?? date('Y'));
        $dataTahun = Ump::with('provinsi')->where('tahun', $tahun)->get();

        $tertinggi = $dataTahun->sortByDesc('nominal')->first();
        $terendah = $dataTahun->sortBy('nominal')->first();
        $rataRata = $dataTahun->avg('nominal') ?? 0;

        return response()->json([
            'status'  => 'success',
            'message' => 'Data statistik UMP berhasil diambil',
            'data'    => [
                'tahun_aktif'    => (int) $tahun,
                'tahun_tersedia' => $tahunTersedia,
                'total_provinsi' => Provinsi::count(),
                'tertinggi'      => $tertinggi ? [
                    'provinsi' => $tertinggi->provinsi->nama_provinsi,
                    'nominal'  => (float) $tertinggi->nominal,
                ] : null,
                'terendah'       => $terendah ? [
                    'provinsi' => $terendah->provinsi->nama_provinsi,
                    'nominal'  => (float) $terendah->nominal,
                ] : null,
                'rata_rata'      => round((float) $rataRata, 2),
            ],
        ], 200);
    }

    /**
     * GET /api/ump/tren?provinsi_id=&dari=&sampai=
     */
    public function tren(Request $request)
    {
        $dari = $request->input('dari');
        $sampai = $request->input('sampai');

        $queryNasional = Ump::selectRaw('tahun, AVG(nominal) as rata_rata, MAX(nominal) as tertinggi, MIN(nominal) as terendah')
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc');

        if ($dari) {
            $queryNasional->where('tahun', '>=', $dari);
        }
        if ($sampai) {
            $queryNasional->where('tahun', '<=', $sampai);
        }

        $trenNasional = $queryNasional->get()->map(fn ($item) => [
            'tahun'     => (int) $item->tahun,
            'rata_rata' => round((float) $item->rata_rata, 2),
            'tertinggi' => (float) $item->tertinggi,
            'terendah'  => (float) $item->terendah,
        ]);

        $provinsiId = $request->input('provinsi_id')
            ?? Provinsi::where('nama_provinsi', 'LIKE', '%Jakarta%')->value('id')
            ?? Provinsi::value('id');

        $provinsi = Provinsi::find($provinsiId);
        $trenProvinsi = [];

        if ($provinsi) {
            $queryProv = Ump::where('provinsi_id', $provinsi->id)->orderBy('tahun', 'asc');
            if ($dari) {
                $queryProv->where('tahun', '>=', $dari);
            }
            if ($sampai) {
                $queryProv->where('tahun', '<=', $sampai);
            }

            $trenProvinsi = $queryProv->get()->map(fn ($item) => [
                'tahun'   => (int) $item->tahun,
                'nominal' => (float) $item->nominal,
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Data tren UMP berhasil diambil',
            'data'    => [
                'nasional' => $trenNasional,
                'provinsi' => [
                    'id'            => $provinsi?->id,
                    'nama_provinsi' => $provinsi?->nama_provinsi,
                    'riwayat'       => $trenProvinsi,
                ],
            ],
        ], 200);
    }

    /**
     * GET /api/ump/perbandingan?tahun=
     */
    public function perbandingan(Request $request)
    {
        $tahunTerbaru = Ump::max('tahun') ?? date('Y');
        $tahun = $request->input('tahun', $tahunTerbaru);

        $semua = Ump::with('provinsi')
            ->where('tahun', $tahun)
            ->orderBy('nominal', 'desc')
            ->get()
            ->map(fn ($item) => [
                'provinsi_id'   => $item->provinsi_id,
                'kode_provinsi' => $item->provinsi->kode_provinsi,
                'nama_provinsi' => $item->provinsi->nama_provinsi,
                'tahun'         => (int) $item->tahun,
                'nominal'       => (float) $item->nominal,
            ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data perbandingan UMP berhasil diambil',
            'data'    => [
                'tahun'     => (int) $tahun,
                'semua'     => $semua,
                'top_10'    => $semua->take(10)->values(),
                'bottom_10' => $semua->sortBy('nominal')->take(10)->values(),
            ],
        ], 200);
    }
}