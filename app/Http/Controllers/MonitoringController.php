<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Production;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $query = Production::with(['material', 'qc.packing']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_produksi', 'like', "%$search%")
                  ->orWhereHas('material', fn($m) => $m->where('nama_material', 'like', "%$search%")
                      ->orWhere('nama_customer', 'like', "%$search%"));
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_produksi', $request->tanggal);
        }

        $productions = $query->latest()->paginate(15)->withQueryString();

        return view('monitoring.index', compact('productions'));
    }
}
