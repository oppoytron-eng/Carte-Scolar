<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'titre',
        'message',
        'data',
        'priorite',
        'date_notification',
        'est_lu',
        'date_lecture',
        'lien',
        'icone',
    ];

    protected $casts = [
        'data' => 'array',
        'est_lu' => 'boolean',
        'date_notification' => 'datetime',
        'date_lecture' => 'datetime',
    ];

    /**
     * Utilisateur destinataire
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Marquer comme lu
     */
    public function marquerCommeLu()
    {
        $this->update([
            'est_lu' => true,
            'date_lecture' => now(),
        ]);
    }
}