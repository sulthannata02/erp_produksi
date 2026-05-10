<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Production;
use App\Models\Material;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Production::with(['material', 'qc.packing']);

        if ($request->customer) {
            $query->whereHas('material', function($q) use ($request) {
                $q->where('nama_customer', $request->customer);
            });
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal_produksi', [$request->start_date, $request->end_date]);
        }

        $productions = $query->latest()->paginate(20);
        $customers = Material::select('nama_customer')->distinct()->pluck('nama_customer');

        return view('admin.reports.index', compact('productions', 'customers'));
    }

    public function pdf(Request $request)
    {
        $query = Production::with(['material', 'qc.packing']);

        if ($request->customer) {
            $query->whereHas('material', function($q) use ($request) {
                $q->where('nama_customer', $request->customer);
            });
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal_produksi', [$request->start_date, $request->end_date]);
        }

        $productions = $query->latest()->get();
        $title = $request->customer ? "Laporan Produksi - " . $request->customer : "Laporan Produksi Menyeluruh";

        $pdf = Pdf::loadView('admin.reports.pdf', compact('productions', 'title'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Report_Produksi_' . now()->format('Ymd_His') . '.pdf');
    }
}
