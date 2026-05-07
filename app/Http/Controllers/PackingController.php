<?php

namespace App\Http\Controllers;

use App\Models\Packing;
use App\Models\Qc;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PackingController extends Controller
{
    public function index(Request $request)
    {
        $query = Packing::with(['qc.production.material']);

        if ($request->filled('customer')) {
            $query->whereHas('qc.production.material', fn($q) => $q->where('nama_customer', $request->customer));
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
                $q->where('kode_packing', 'like', "%$search%")
                  ->orWhere('operator', 'like', "%$search%")
                  ->orWhereHas('qc.production.material', fn($m) => $m->where('nama_material', 'like', "%$search%"));
            });
        }

        $packings  = $query->latest()->paginate(10)->withQueryString();
        $customers = Material::distinct()->pluck('nama_customer')->filter()->sort()->values();

        return view('packings.index', compact('packings', 'customers'));
    }

    public function create()
    {
        // Hanya QC yang sudah selesai & belum punya packing
        $qcs = Qc::with('production.material')
            ->where('hasil', 'good')
            ->whereDoesntHave('packing')
            ->get();
        return view('packings.create', compact('qcs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'qc_id'      => 'required|exists:qcs,id',
            'jumlah_fg'  => 'required|integer|min:0',
            'jumlah_ng'  => 'required|integer|min:0',
            'keterangan' => 'nullable|string|max:500',
            'operator'   => 'required|string|max:255',
        ]);

        // Generate kode packing: PKG-YYMMDD-XXX
        $date  = Carbon::now()->format('ymd');
        $count = Packing::whereDate('created_at', now()->toDateString())->count() + 1;
        $kode  = 'PKG-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        Packing::create([
            'kode_packing' => $kode,
            'qc_id'        => $request->qc_id,
            'jumlah_fg'    => $request->jumlah_fg,
            'jumlah_ng'    => $request->jumlah_ng,
            'keterangan'   => $request->keterangan,
            'operator'     => $request->operator,
            'status'       => 'proses',
        ]);

        // Update status QC
        Qc::findOrFail($request->qc_id)->update(['status' => 'selesai']);

        return redirect()->route('packings.index')->with('success', 'Data packing berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $packing = Packing::with(['qc.production.material'])->findOrFail($id);
        return view('packings.show', compact('packing'));
    }

    public function edit(string $id)
    {
        $packing = Packing::with(['qc.production.material'])->findOrFail($id);
        return view('packings.edit', compact('packing'));
    }

    public function update(Request $request, string $id)
    {
        $packing = Packing::findOrFail($id);
        $request->validate([
            'jumlah_fg'  => 'required|integer|min:0',
            'jumlah_ng'  => 'required|integer|min:0',
            'keterangan' => 'nullable|string|max:500',
            'operator'   => 'required|string|max:255',
            'status'     => 'required|in:proses,selesai',
        ]);

        $packing->update($request->only(['jumlah_fg','jumlah_ng','keterangan','operator','status']));

        return redirect()->route('packings.index')->with('success', 'Data packing berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        Packing::findOrFail($id)->delete();
        return redirect()->route('packings.index')->with('success', 'Data packing berhasil dihapus!');
    }
}