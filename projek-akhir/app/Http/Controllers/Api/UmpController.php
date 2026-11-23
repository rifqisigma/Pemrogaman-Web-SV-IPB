<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUmpRequest;
use App\Http\Requests\UpdateUmpRequest;
use App\Models\Ump;
use Illuminate\Http\Request;

class UmpController extends Controller
{
    public function index(Request $request)
    {
        $query = Ump::with('provinsi');

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        if ($request->filled('provinsi_id')) {
            $query->where('provinsi_id', $request->provinsi_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('provinsi', function ($q) use ($search) {
                $q->where('nama_provinsi', 'LIKE', "%{$search}%");
            });
        }

        $query->orderBy('tahun', 'desc')->orderBy('provinsi_id', 'asc');

        if ($request->filled('per_page')) {
            $paginated = $query->paginate((int) $request->per_page);

            return response()->json([
                'status'  => 'success',
                'message' => 'Daftar data UMP berhasil diambil',
                'data'    => $paginated->items(),
                'meta'    => [
                    'current_page' => $paginated->currentPage(),
                    'last_page'    => $paginated->lastPage(),
                    'per_page'     => $paginated->perPage(),
                    'total'        => $paginated->total(),
                ],
            ], 200);
        }

        $data = $query->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar data UMP berhasil diambil',
            'data'    => $data,
        ], 200);
    }

    public function store(StoreUmpRequest $request)
    {
        $ump = Ump::create($request->validated());
        $ump->load('provinsi');

        return response()->json([
            'status'  => 'success',
            'message' => 'Data UMP berhasil ditambahkan',
            'data'    => $ump,
        ], 201);
    }

    public function show(Ump $ump)
    {
        $ump->load('provinsi');

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail data UMP berhasil diambil',
            'data'    => $ump,
        ], 200);
    }

    public function update(UpdateUmpRequest $request, Ump $ump)
    {
        $ump->update($request->validated());
        $ump->load('provinsi');

        return response()->json([
            'status'  => 'success',
            'message' => 'Data UMP berhasil diperbarui',
            'data'    => $ump,
        ], 200);
    }

    public function destroy(Ump $ump)
    {
        $ump->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data UMP berhasil dihapus',
        ], 200);
    }
}
