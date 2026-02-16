<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SurveillantPhotoController extends Controller
{
    public function validation()
    {
        // your validation logic here
        return view('surveillant.photos.validation'); // adjust if needed
    }
}
