<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Etablissement;
use Carbon\Carbon;

class UserEtablissementSeeder extends Seeder
{
    public function run()
{
    // On récupère l'utilisateur ID 1 (Administrateur)
    $user = \App\Models\User::find(1);
    
    // On récupère le premier établissement créé en base
    $etablissement = \App\Models\Etablissement::first();

    if ($user && $etablissement) {
        $user->etablissements()->syncWithoutDetaching([
            $etablissement->id => [
                'is_principal' => true,
                'role_etablissement' => 'Proviseur', // Valeur courte pour éviter l'erreur SQL
                'date_debut' => now(),                // Obligatoire selon votre erreur précédente
            ]
        ]);
        
        $this->command->info("Liaison creee entre {$user->nom} et {$etablissement->nom}");
    } else {
        $this->command->error("Erreur : Verifiez que l'utilisateur 1 et un etablissement existent.");
    }
}
}