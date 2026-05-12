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

        return view('operator.productions.index', compact('productions', 'customers'));
    }

    public function create()
    {
        $materialsList = Material::where('aktual_stok', '>', 0)->get();
        $operatorsList = \App\Models\User::where('role', 'operator')->get();
        return view('operator.productions.create', compact('materialsList', 'operatorsList'));
    }

    public function edit($id)
    {
        $production = Production::findOrFail($id);
        $operatorsList = \App\Models\User::where('role', 'operator')->get();
        return view('operator.productions.edit', compact('production', 'operatorsList'));
    }

    public function store(Request $request)
    {
        $rules = [
            'material_id'      => 'required|exists:materials,id',
            'target_hanger'    => 'required|integer|min:1',
            'tanggal_produksi' => 'required|date',
        ];

        if (auth()->user()->role !== 'operator') {
            $rules['operator'] = 'required|string|max:255';
        }

        $request->validate($rules);

        $operatorName = auth()->user()->role === 'operator' ? auth()->user()->name : $request->operator;

        $material = Material::findOrFail($request->material_id);
        
        // Generate kode produksi: PRD-YYMMDD-XXX
        $date  = Carbon::parse($request->tanggal_produksi)->format('ymd');
        $count = Production::whereDate('tanggal_produksi', $request->tanggal_produksi)->count() + 1;
        $kode  = 'PRD-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

        $production = Production::create([
            'kode_produksi'  => $kode,
            'material_id'    => $request->material_id,
            'target_hanger'  => $request->target_hanger,
            'jumlah_hanger'  => 0, // Belum dikerjakan
            'jumlah_produksi'=> 0, // Belum dikerjakan
            'operator'       => $operatorName,
            'tanggal_produksi'=> $request->tanggal_produksi,
            'status'         => 'rencana',
        ]);

        $assignedOperator = \App\Models\User::where('role', 'operator')->where('name', $operatorName)->first();
        if ($assignedOperator) {
            $assignedOperator->notify(new \App\Notifications\ProductionAssignedNotification($production));
        }

        // STOK TIDAK DIPOTONG DI SINI (Dipotong saat Operator Validasi/Mulai)
        
        return redirect()->route('productions.index')->with('success', 'Rencana Produksi (Blueprint) berhasil dibuat!');
    }

    public function show(string $id)
    {
        $production = Production::with(['material', 'qc'])->findOrFail($id);
        return view('operator.productions.show', compact('production'));
    }

    public function update(Request $request, string $id)
    {
        $production = Production::findOrFail($id);
        $request->validate([
            'operator'         => auth()->user()->role === 'operator' ? 'nullable' : 'required|string|max:255',
            'target_hanger'    => 'required|integer|min:1',
            'jumlah_hanger'    => 'nullable|integer|min:0',
            'status'           => 'required|in:rencana,proses,selesai',
            'tanggal_produksi' => 'required|date',
        ]);

        $material = $production->material;
        
        // Gunakan jumlah_hanger (Actual) jika diinput, jika tidak (rencana) gunakan 0
        $actualHanger = ($request->status === 'rencana') ? 0 : ($request->jumlah_hanger ?? $production->jumlah_hanger);
        $totalQtyActual = $actualHanger * $material->qty_per_hanger;

        $updateData = [
            'target_hanger'    => $request->target_hanger,
            'jumlah_hanger'    => $actualHanger,
            'jumlah_produksi'  => $totalQtyActual,
            'status'           => $request->status,
            'tanggal_produksi' => $request->tanggal_produksi,
        ];

        if ($request->filled('operator')) {
            $updateData['operator'] = $request->operator;
        }

        $production->update($updateData);

        return redirect()->route('productions.index')->with('success', 'Data produksi berhasil diperbarui!');
    }

    public function status($id)
    {
        $production = Production::with('material')->findOrFail($id);
        return view('operator.productions.status', compact('production'));
    }

    public function updateStatus(Request $request, $id)
    {
        $production = Production::findOrFail($id);
        
        $request->validate([
            'status'        => 'required|in:rencana,proses,selesai',
            'jumlah_hanger' => 'required|integer|min:1',
        ]);

        $material = $production->material;
        
        // Update data
        $production->update([
            'status'          => $request->status,
            'jumlah_hanger'   => $request->jumlah_hanger,
            'jumlah_produksi' => $request->jumlah_hanger * $material->qty_per_hanger,
        ]);

        return redirect()->route('productions.index')->with('success', 'Status produksi berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $production = Production::findOrFail($id);
        $production->delete();
        return redirect()->route('productions.index')->with('success', 'Data produksi berhasil dihapus!');
    }
}
