<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type_action',
        'module',
        'description',
        'entite_type',
        'entite_id',
        'donnees_avant',
        'donnees_apres',
        'adresse_ip',
        'user_agent',
        'statut',
        'message_erreur',
    ];

    protected $casts = [
        'donnees_avant' => 'array',
        'donnees_apres' => 'array',
    ];

    /**
     * Utilisateur qui a effectué l'action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}