<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\MaterialMasuk;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = MaterialMasuk::with('material');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('search')) {
            $search = $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_dn', 'like', "%$search%")
                  ->orWhereHas('material', function($m) use ($search) {
                      $m->where('nama_material', 'like', "%$search%")
                        ->orWhere('kode_part', 'like', "%$search%")
                        ->orWhere('nama_customer', 'like', "%$search%");
                  });
            });
        }

        $materialMasuks = $query->latest()->paginate(10)->withQueryString();
        
        return view('operator.material_masuks.index', compact('materialMasuks'));
    }

    public function create()
    {
        $materialsList = Material::all();
        return view('operator.material_masuks.create', compact('materialsList'));
    }

    public function edit($id)
    {
        $materialMasuk = MaterialMasuk::findOrFail($id);
        $materialsList = Material::all();
        return view('operator.material_masuks.edit', compact('materialMasuk', 'materialsList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'qty_masuk'   => 'required|integer|min:1',
            'no_dn'       => 'required|string|max:255',
            'tanggal'     => 'required|date',
        ]);

        $material = Material::findOrFail($request->material_id);

        MaterialMasuk::create([
            'material_id' => $request->material_id,
            'qty_masuk'   => $request->qty_masuk,
            'no_dn'       => $request->no_dn,
            'tanggal'     => $request->tanggal,
            'operator'    => auth()->user()->name,
        ]);

        // Add stock
        $material->aktual_stok += $request->qty_masuk;
        $material->save();

        return redirect()->route('material-masuks.index')->with('success', 'Data barang datang berhasil ditambahkan & stok bertambah!');
    }

    public function update(Request $request, string $id)
    {
        $materialMasuk = MaterialMasuk::findOrFail($id);
        
        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'qty_masuk'   => 'required|integer|min:1',
            'no_dn'       => 'required|string|max:255',
            'tanggal'     => 'required|date',
        ]);

        $materialLama = Material::findOrFail($materialMasuk->material_id);
        $materialBaru = Material::findOrFail($request->material_id);

        if ($materialMasuk->material_id == $request->material_id) {
            // Same material, adjust difference
            $selisih = $request->qty_masuk - $materialMasuk->qty_masuk;
            $materialLama->aktual_stok += $selisih;
            $materialLama->save();
        } else {
            // Different material, rollback old, add to new
            $materialLama->aktual_stok -= $materialMasuk->qty_masuk;
            $materialLama->save();

            $materialBaru->aktual_stok += $request->qty_masuk;
            $materialBaru->save();
        }

        $materialMasuk->update([
            'material_id' => $request->material_id,
            'qty_masuk'   => $request->qty_masuk,
            'no_dn'       => $request->no_dn,
            'tanggal'     => $request->tanggal,
        ]);

        return redirect()->route('material-masuks.index')->with('success', 'Data barang datang berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $materialMasuk = MaterialMasuk::findOrFail($id);
        
        // Rollback stock
        $material = Material::findOrFail($materialMasuk->material_id);
        $material->aktual_stok -= $materialMasuk->qty_masuk;
        $material->save();

        $materialMasuk->delete();

        return redirect()->route('material-masuks.index')->with('success', 'Data barang datang berhasil dihapus!');
    }
}
