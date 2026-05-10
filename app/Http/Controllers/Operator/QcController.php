<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;

use App\Models\Qc;
use App\Models\Production;
use App\Models\Material;
use Illuminate\Http\Request;

class QcController extends Controller
{
    public function index(Request $request)
    {
        $operatorName = auth()->user()->name;
        $query = Qc::with(['production.material'])
            ->whereHas('production', fn($q) => $q->where('operator', $operatorName));

        if ($request->filled('customer')) {
            $query->whereHas('production.material', fn($q) => $q->where('nama_customer', $request->customer));
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('production', fn($p) => $p->where('kode_produksi', 'like', "%$search%"))
                  ->orWhereHas('production.material', fn($m) => $m->where('nama_material', 'like', "%$search%"));
            });
        }

        $qcs       = $query->latest()->paginate(10)->withQueryString();
        $customers = Material::distinct()->pluck('nama_customer')->filter()->sort()->values();

        $productionsList = Production::with('material')
            ->where('operator', $operatorName)
            ->whereDoesntHave('qc')
            ->get();

        return view('operator.qcs.index', compact('qcs', 'customers', 'productionsList'));
    }

    public function create()
    {
        // Hanya produksi yang belum punya QC & ditugaskan ke operator ini
        $productions = Production::with('material')
            ->where('operator', auth()->user()->name)
            ->whereDoesntHave('qc')
            ->get();
        return view('operator.qcs.create', compact('productions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'production_id' => 'required|exists:productions,id',
            'qty_qc'        => 'required|integer|min:1',
            'jumlah_fg'     => 'required|integer|min:0',
            'jumlah_ng'     => 'required|integer|min:0',
            'keterangan'    => 'nullable|string|max:500',
        ]);

        Qc::create([
            'production_id' => $request->production_id,
            'qty_qc'        => $request->qty_qc,
            'jumlah_fg'     => $request->jumlah_fg,
            'jumlah_ng'     => $request->jumlah_ng,
            'keterangan'    => $request->keterangan,
            'status'        => 'proses',
        ]);

        // Update status produksi
        $production = Production::findOrFail($request->production_id);
        $production->update(['status' => 'selesai']);

        $admins = \App\Models\User::where('role', 'admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\OperatorActionNotification(
            'Laporan QC Baru',
            "Operator " . auth()->user()->name . " telah menginput data QC untuk produksi {$production->kode_produksi}.",
            route('monitoring.index'),
            'ph-shield-check',
            'purple'
        ));

        return redirect()->route('qcs.index')->with('success', 'Data QC berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $qc = Qc::with(['production.material', 'packing'])->findOrFail($id);
        return view('operator.qcs.show', compact('qc'));
    }

    public function edit(string $id)
    {
        $qc = Qc::findOrFail($id);
        return view('operator.qcs.edit', compact('qc'));
    }

    public function update(Request $request, string $id)
    {
        $qc = Qc::findOrFail($id);
        $request->validate([
            'jumlah_fg'  => 'required|integer|min:0',
            'jumlah_ng'  => 'required|integer|min:0',
            'keterangan' => 'nullable|string|max:500',
            'status'     => 'required|in:proses,selesai',
        ]);

        $qc->update([
            'jumlah_fg'  => $request->jumlah_fg,
            'jumlah_ng'  => $request->jumlah_ng,
            'keterangan' => $request->keterangan,
            'status'     => $request->status,
        ]);

        return redirect()->route('qcs.index')->with('success', 'Data QC berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        Qc::findOrFail($id)->delete();
        return redirect()->route('qcs.index')->with('success', 'Data QC berhasil dihapus!');
    }
}

