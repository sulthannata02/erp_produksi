<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qc extends Model
{
    protected $fillable = [
        'production_id',
        'qty_qc',
        'jumlah_fg',
        'jumlah_ng',
        'keterangan',
        'status',     // 'proses' / 'selesai'
    ];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function packing()
    {
        return $this->hasOne(Packing::class);
    }
}
