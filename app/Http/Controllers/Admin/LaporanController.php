<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Material;
use App\Models\Production;
use App\Models\Packing;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $customers    = Material::distinct()->pluck('nama_customer')->filter()->sort()->values();
        $selectedCust = $request->get('customer', '');
        $dateFrom     = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo       = $request->get('date_to', now()->toDateString());

        $query = Production::with(['material', 'qc.packing'])
            ->whereBetween('tanggal_produksi', [$dateFrom, $dateTo]);

        if ($request->filled('customer')) {
            $query->whereHas('material', fn($q) => $q->where('nama_customer', $request->customer));
        }

        $productions = $request->has('tampilkan') ? $query->get() : collect();

        // Hitung total
        $totalProduksi = $productions->sum('jumlah_produksi');
        $totalFgOk     = $productions->sum(fn($p) => optional($p->qc)->hasil === 'good'     ? optional($p->qc)->qty_qc : 0);
        $totalNg       = $productions->sum(fn($p) => optional($p->qc)->hasil === 'not_good' ? optional($p->qc)->qty_qc : 0);
        $totalPackFg   = $productions->sum(fn($p) => optional(optional($p->qc)->packing)->jumlah_fg ?? 0);
        $totalPackNg   = $productions->sum(fn($p) => optional(optional($p->qc)->packing)->jumlah_ng ?? 0);

        return view('admin.laporan.index', compact(
            'productions', 'customers', 'selectedCust',
            'dateFrom', 'dateTo',
            'totalProduksi', 'totalFgOk', 'totalNg', 'totalPackFg', 'totalPackNg'
        ));
    }

    public function export(Request $request)
    {
        $selectedCust = $request->get('customer', '');
        $dateFrom     = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo       = $request->get('date_to', now()->toDateString());

        $query = Production::with(['material', 'qc.packing'])
            ->whereBetween('tanggal_produksi', [$dateFrom, $dateTo]);

        if ($request->filled('customer')) {
            $query->whereHas('material', fn($q) => $q->where('nama_customer', $request->customer));
        }

        $productions = $query->get();

        $totalProduksi = $productions->sum('jumlah_produksi');
        $totalFgOk     = $productions->sum(fn($p) => optional($p->qc)->hasil === 'good'     ? optional($p->qc)->qty_qc : 0);
        $totalNg       = $productions->sum(fn($p) => optional($p->qc)->hasil === 'not_good' ? optional($p->qc)->qty_qc : 0);
        $totalPackFg   = $productions->sum(fn($p) => optional(optional($p->qc)->packing)->jumlah_fg ?? 0);
        $totalPackNg   = $productions->sum(fn($p) => optional(optional($p->qc)->packing)->jumlah_ng ?? 0);

        $pdf = Pdf::loadview('admin.laporan.pdf', compact(
            'productions', 'selectedCust', 'dateFrom', 'dateTo',
            'totalProduksi', 'totalFgOk', 'totalNg', 'totalPackFg', 'totalPackNg'
        ))->setPaper('a4', 'landscape');

        $filename = 'laporan-produksi-' . $dateFrom . '-sd-' . $dateTo . '.pdf';

        return $pdf->download($filename);
    }
}

