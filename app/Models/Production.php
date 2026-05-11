<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    protected $fillable = [
        'kode_produksi',
        'material_id',
        'target_hanger',
        'jumlah_hanger',
        'jumlah_produksi',
        'operator',
        'tanggal_produksi',
        'status',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function qc()
    {
        return $this->hasOne(Qc::class);
    }
}
