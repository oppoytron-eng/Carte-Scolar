<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarteScolaire;

class SurveillantController extends Controller
{
    public function impression()
    {
        $etablissement = auth()->user()->etablissementPrincipal();

        $cartesEnAttente = 0;
        $cartesImprimeesMois = 0;

        if ($etablissement) {
            $cartesEnAttente = CarteScolaire::where('etablissement_id', $etablissement->id)
                ->where('statut', 'Carte_generee')
                ->count();

            $cartesImprimeesMois = CarteScolaire::where('etablissement_id', $etablissement->id)
                ->where('statut', 'Carte_imprimee')
                ->whereMonth('date_impression', now()->month)
                ->whereYear('date_impression', now()->year)
                ->count();
        }

        return view('surveillant.impression.index', compact('cartesEnAttente', 'cartesImprimeesMois'));
    }
}
