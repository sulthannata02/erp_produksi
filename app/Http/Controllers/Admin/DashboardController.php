<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\Production;
use App\Models\Qc;
use App\Models\Packing;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
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

        return view('admin.dashboard', compact(
            'totalMaterial', 'totalProduksi', 'totalQc', 'totalPacking',
            'prioritas', 'grafikData', 'grafikLabel'
        ));
    }
}
