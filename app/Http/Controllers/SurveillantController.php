<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SurveillantController extends Controller
{
    public function impression()
    {
        // Logique pour l'impression
        return view('surveillant.impression.index'); // Assure-toi que cette view existe
    }
}
