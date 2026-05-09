<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SintomaController extends Controller
{
    public function create()
    {
        return view('sintomas.create');
    }
}