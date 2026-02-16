<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Photo;

class SurveillantPhotoController extends Controller
{
    public function validation()
    {
        $etablissement = auth()->user()->etablissementPrincipal();

        $photosEnAttente = collect();
        if ($etablissement) {
            $photosEnAttente = Photo::whereHas('eleve', function($q) use ($etablissement) {
                    $q->where('etablissement_id', $etablissement->id);
                })
                ->where('statut', 'En_attente')
                ->with(['eleve.classe', 'operateur'])
                ->orderBy('date_capture', 'asc')
                ->paginate(20);
        }

        return view('surveillant.photos.validation', compact('photosEnAttente'));
    }
}
