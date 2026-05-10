<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'nama_customer',
        'nama_material',
        'kode_part',
        'tanggal_masuk',
        'jumlah',
        'aktual_stok',
        'qty_per_hanger',
        'qty_per_box',
        'satuan',
        'gambar',
    ];

    public function productions()
    {
        return $this->hasMany(Production::class);
    }
}
