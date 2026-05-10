<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Models\Material;
use Illuminate\Http\Request;

class ProductionWorkController extends Controller
{
    /**
     * Tampilkan daftar SPK (Rencana) yang ditugaskan ke operator ini.
     */
    public function index()
    {
        $operatorName = auth()->user()->name;
        
        $plans = Production::with('material')
            ->where('operator', $operatorName)
            ->where('status', 'rencana')
            ->latest()
            ->get();

        return view('operator.production_work.index', compact('plans'));
    }

    public function showStart($id)
    {
        $production = Production::with('material')->findOrFail($id);
        
        // Pastikan status masih rencana
        if ($production->status !== 'rencana') {
            return redirect()->route('dashboard')->with('error', 'Produksi ini sudah diproses atau selesai.');
        }

        return view('operator.production_work.start', compact('production'));
    }

    /**
     * Validasi pengerjaan produksi (Blueprint -> Actual)
     * Di sini stok fisik beneran dipotong.
     */
    public function start(Request $request, $id)
    {
        $production = Production::findOrFail($id);
        
        // Pastikan status masih rencana
        if ($production->status !== 'rencana') {
            return back()->with('error', 'Produksi ini sudah diproses atau selesai.');
        }

        $request->validate([
            'jumlah_hanger' => 'required|integer|min:1',
        ]);

        $material = $production->material;
        $total_qty_actual = $request->jumlah_hanger * $material->qty_per_hanger;

        // Validasi Stok Fisik (Aktual)
        if ($total_qty_actual > $material->aktual_stok) {
            return back()->with('error', 'Stok fisik di gudang tidak mencukupi! Tersedia: ' . $material->aktual_stok . ' ' . $material->satuan);
        }

        // Potong Stok & Update Data Produksi ke 'proses'
        $material->aktual_stok -= $total_qty_actual;
        $material->save();

        $production->update([
            'jumlah_hanger'   => $request->jumlah_hanger,
            'jumlah_produksi' => $total_qty_actual,
            'status'          => 'proses',
        ]);

        return redirect()->route('dashboard')->with('success', 'Produksi berhasil dimulai! Stok fisik telah dikurangi.');
    }
}
