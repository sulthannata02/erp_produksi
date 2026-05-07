<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Production;
use App\Models\Qc;
use App\Models\Packing;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;

        if ($role === 'operator') {
            return $this->operatorDashboard();
        }

        return $this->adminDashboard();
    }

    // ─── Dashboard Admin ───────────────────────────────────────
    private function adminDashboard()
    {
        $totalMaterial  = Material::count();
        $totalProduksi  = Production::count();
        $totalQc        = Qc::count();
        $totalPacking   = Packing::count();

        // Prioritas produksi (belum selesai, urutkan by tanggal)
        $prioritas = Production::with('material')
            ->orderBy('tanggal_produksi', 'asc')
            ->take(10)
            ->get();

        // Grafik 30 hari terakhir
        $grafikLabel = [];
        $grafikData  = [];
        for ($i = 29; $i >= 0; $i--) {
            $tanggal       = Carbon::now()->subDays($i);
            $grafikLabel[] = $tanggal->format('d/m');
            $grafikData[]  = Production::whereDate('tanggal_produksi', $tanggal->toDateString())->sum('jumlah_produksi');
        }

        return view('dashboard.admin', compact(
            'totalMaterial', 'totalProduksi', 'totalQc', 'totalPacking',
            'prioritas', 'grafikData', 'grafikLabel'
        ));
    }

    // ─── Dashboard Operator ────────────────────────────────────
    private function operatorDashboard()
    {
        // Produksi yang belum di-QC
        $beluQc = Production::with('material')
            ->whereDoesntHave('qc')
            ->latest()
            ->get();

        // Produksi sudah QC (good) tapi belum packing
        $belumPacking = Production::with(['material', 'qc'])
            ->whereHas('qc', fn($q) => $q->where('hasil', 'good'))
            ->whereHas('qc', fn($q) => $q->whereDoesntHave('packing'))
            ->latest()
            ->get();

        // Stat ringkas
        $totalBelumQc     = $beluQc->count();
        $totalBelumPacking = $belumPacking->count();
        $totalQcSelesai   = Qc::where('status', 'selesai')->count();
        $totalPackSelesai = Packing::where('status', 'selesai')->count();

        return view('dashboard.operator', compact(
            'beluQc', 'belumPacking',
            'totalBelumQc', 'totalBelumPacking',
            'totalQcSelesai', 'totalPackSelesai'
        ));
    }
}
