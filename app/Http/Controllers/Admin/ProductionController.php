<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Production;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ProductionController extends Controller
{
    public function index(Request $request)
    {
        $query = Production::with('material');

        if ($request->filled('customer')) {
            $query->whereHas('material', fn($q) => $q->where('nama_customer', $request->customer));
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_produksi', $request->tanggal);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_produksi', 'like', "%$search%")
                  ->orWhere('operator', 'like', "%$search%")
                  ->orWhereHas('material', fn($m) => $m->where('nama_material', 'like', "%$search%"));
            });
        }

        $productions = $query->latest()->paginate(10)->withQueryString();
        $customers   = Material::distinct()->pluck('nama_customer')->filter()->sort()->values();

        return view('admin.productions.index', compact('productions', 'customers'));
    }

    public function create()
    {
        $materials = Material::where('jumlah', '>', 0)->get();
        $operators = \App\Models\User::where('role', 'operator')->get();
        return view('admin.productions.create', compact('materials', 'operators'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_id'    => 'required|exists:materials,id',
            'jumlah_produksi'=> 'required|integer|min:1',
            'operator'       => 'required|string|max:255',
            'tanggal_produksi' => 'required|date',
        ]);

        $material = Material::findOrFail($request->material_id);

        if ($request->jumlah_produksi > $material->jumlah) {
            return back()->with('error', 'Stok material tidak mencukupi! Stok tersedia: ' . $material->jumlah . ' ' . $material->satuan);
        }

        // Generate kode produksi: PRD-YYMMDD-XXX
        $date  = Carbon::parse($request->tanggal_produksi)->format('ymd');
        $count = Production::whereDate('tanggal_produksi', $request->tanggal_produksi)->count() + 1;
        $kode  = 'PRD-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        Production::create([
            'kode_produksi'  => $kode,
            'material_id'    => $request->material_id,
            'jumlah_produksi'=> $request->jumlah_produksi,
            'operator'       => $request->operator,
            'tanggal_produksi'=> $request->tanggal_produksi,
            'status'         => 'proses',
        ]);

        // Kurangi stok material
        $material->jumlah -= $request->jumlah_produksi;
        $material->save();

        return redirect()->route('productions.index')->with('success', 'Data produksi berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $production = Production::with(['material', 'qc'])->findOrFail($id);
        return view('admin.productions.show', compact('production'));
    }

    public function edit(string $id)
    {
        $production = Production::findOrFail($id);
        $materials  = Material::all();
        $operators = \App\Models\User::where('role', 'operator')->get();
        return view('admin.productions.edit', compact('production', 'materials', 'operators'));
    }

    public function update(Request $request, string $id)
    {
        $production = Production::findOrFail($id);
        $request->validate([
            'operator' => 'required|string|max:255',
            'status'   => 'required|in:proses,selesai',
        ]);

        $production->update([
            'operator' => $request->operator,
            'status'   => $request->status,
        ]);

        return redirect()->route('productions.index')->with('success', 'Data produksi berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $production = Production::findOrFail($id);
        $production->delete();
        return redirect()->route('productions.index')->with('success', 'Data produksi berhasil dihapus!');
    }
}
