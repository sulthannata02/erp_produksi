<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialMasuk extends Model
{
    protected $fillable = [
        'material_id',
        'qty_masuk',
        'no_dn',
        'tanggal',
        'operator',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
