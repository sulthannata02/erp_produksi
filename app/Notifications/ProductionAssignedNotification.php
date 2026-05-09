<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductionAssignedNotification extends Notification
{
    use Queueable;

    public $production;

    public function __construct($production)
    {
        $this->production = $production;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Tugas Produksi Baru',
            'message' => "Anda ditugaskan untuk memproses produksi dengan kode {$this->production->kode_produksi}.",
            'icon' => 'ph-factory',
            'color' => 'green',
            'url' => route('qcs.create') . '?production=' . $this->production->id,
        ];
    }
}
