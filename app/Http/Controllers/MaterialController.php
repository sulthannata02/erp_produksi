<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::query();

        // Filter customer
        if ($request->filled('customer')) {
            $query->where('nama_customer', $request->customer);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_material', 'like', "%$search%")
                  ->orWhere('kode_part', 'like', "%$search%")
                  ->orWhere('nama_customer', 'like', "%$search%");
            });
        }

        $materials = $query->latest()->paginate(10)->withQueryString();
        $customers = Material::distinct()->pluck('nama_customer')->filter()->sort()->values();

        return view('materials.index', compact('materials', 'customers'));
    }

    public function create()
    {
        return view('materials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'nama_material' => 'required|string|max:255',
            'kode_part'     => 'required|string|max:100',
            'jumlah'        => 'required|integer|min:0',
            'satuan'        => 'required|string|max:50',
            'tanggal_masuk' => 'required|date',
            'gambar'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('materials', 'public');
        }

        Material::create([
            'nama_customer' => $validated['nama_customer'],
            'nama_material' => $validated['nama_material'],
            'kode_part'     => $validated['kode_part'],
            'jumlah'        => $validated['jumlah'],
            'satuan'        => $validated['satuan'],
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'gambar'        => $gambarPath,
        ]);

        return redirect()->route('materials.index')->with('success', 'Material berhasil ditambahkan!');
    }

    public function show(string $id)
    {
        $material = Material::findOrFail($id);
        return view('materials.show', compact('material'));
    }

    public function edit(string $id)
    {
        $material = Material::findOrFail($id);
        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, string $id)
    {
        $material = Material::findOrFail($id);

        $validated = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'nama_material' => 'required|string|max:255',
            'kode_part'     => 'required|string|max:100',
            'jumlah'        => 'required|integer|min:0',
            'satuan'        => 'required|string|max:50',
            'tanggal_masuk' => 'required|date',
            'gambar'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'nama_customer' => $validated['nama_customer'],
            'nama_material' => $validated['nama_material'],
            'kode_part'     => $validated['kode_part'],
            'jumlah'        => $validated['jumlah'],
            'satuan'        => $validated['satuan'],
            'tanggal_masuk' => $validated['tanggal_masuk'],
        ];

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($material->gambar) {
                Storage::disk('public')->delete($material->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('materials', 'public');
        }

        $material->update($data);

        return redirect()->route('materials.index')->with('success', 'Material berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $material = Material::findOrFail($id);
        if ($material->gambar) {
            Storage::disk('public')->delete($material->gambar);
        }
        $material->delete();
        return redirect()->route('materials.index')->with('success', 'Material berhasil dihapus!');
    }
}
