<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Models\Qc;
use App\Models\Packing;

class DashboardController extends Controller
{
    public function index()
    {
        $operatorName = auth()->user()->name;

        // Prioritas Produksi (Semua Data)
        $prioritas = Production::with('material')
            ->orderBy('tanggal_produksi', 'asc')
            ->take(10)
            ->get();

        // Produksi yang belum di-QC
        $beluQc = Production::with('material')
            ->where('operator', $operatorName)
            ->where('status', 'proses')
            ->whereDoesntHave('qc')
            ->latest()
            ->get();

        // Produksi sudah QC (good) tapi belum packing
        $belumPacking = Production::with(['material', 'qc'])
            ->where('operator', $operatorName)
            ->whereHas('qc', fn($q) => $q->where('jumlah_fg', '>', 0))
            ->whereHas('qc', fn($q) => $q->whereDoesntHave('packing'))
            ->latest()
            ->get();

        // Stat ringkas (Global)
        $totalBarangDatang = \App\Models\MaterialMasuk::count();
        $totalProduksi     = Production::count();
        $totalQc           = Qc::count();
        $totalPacking      = Packing::count();

        return view('operator.dashboard', compact(
            'prioritas', 'beluQc', 'belumPacking',
            'totalBarangDatang', 'totalProduksi', 'totalQc', 'totalPacking'
        ));
    }
}
