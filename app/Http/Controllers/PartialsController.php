<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PartialsController extends Controller
{
    public function about()
    {
        view('partials.about');
    }
}
