<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packing extends Model
{
    protected $fillable = [
        'kode_packing',
        'qc_id',
        'jumlah_fg',
        'jumlah_ng',
        'jumlah_box',
        'keterangan',
        'operator',
        'status',
    ];

    public function qc()
    {
        return $this->belongsTo(Qc::class);
    }
}
