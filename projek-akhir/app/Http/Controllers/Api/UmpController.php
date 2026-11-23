<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ump;
use Illuminate\Http\Request;

class UmpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        $data = $query->orderBy('tahun', 'desc')->orderBy('provinsi_id', 'asc')->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar data UMP berhasil diambil',
            'data'    => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $ump = Ump::create($request->validated());
        $ump->load('provinsi');

        return response()->json([
            'status'  => 'success',
            'message' => 'Data UMP berhasil ditambahkan',
            'data'    => $ump,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ump $ump)
    {
        $ump->load('provinsi');

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail data UMP berhasil diambil',
            'data'    => $ump,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ump $ump)
    {
        $ump->update($request->validated());
        $ump->load('provinsi');

        return response()->json([
            'status'  => 'success',
            'message' => 'Data UMP berhasil diperbarui',
            'data'    => $ump,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ump $ump)
    {
        $ump->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data UMP berhasil dihapus',
        ], 200);
    }
}
