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

        // Produksi yang belum di-QC
        $beluQc = Production::with('material')
            ->where('operator', $operatorName)
            ->whereDoesntHave('qc')
            ->latest()
            ->get();

        // Produksi sudah QC (good) tapi belum packing
        $belumPacking = Production::with(['material', 'qc'])
            ->where('operator', $operatorName)
            ->whereHas('qc', fn($q) => $q->where('hasil', 'good'))
            ->whereHas('qc', fn($q) => $q->whereDoesntHave('packing'))
            ->latest()
            ->get();

        // Stat ringkas
        $totalBelumQc     = $beluQc->count();
        $totalBelumPacking = $belumPacking->count();
        $totalQcSelesai   = Qc::whereHas('production', fn($q) => $q->where('operator', $operatorName))->where('status', 'selesai')->count();
        $totalPackSelesai = Packing::whereHas('qc.production', fn($q) => $q->where('operator', $operatorName))->where('status', 'selesai')->count();

        return view('operator.dashboard', compact(
            'beluQc', 'belumPacking',
            'totalBelumQc', 'totalBelumPacking',
            'totalQcSelesai', 'totalPackSelesai'
        ));
    }
}
