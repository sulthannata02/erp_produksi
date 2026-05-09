<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MaterialAddedNotification extends Notification
{
    use Queueable;

    public $material;

    public function __construct($material)
    {
        $this->material = $material;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Material Baru Ditambahkan',
            'message' => "Material {$this->material->nama_material} sejumlah " . number_format($this->material->jumlah) . " {$this->material->satuan} telah masuk ke gudang.",
            'icon' => 'ph-package',
            'color' => 'blue',
            'url' => null,
        ];
    }
}
