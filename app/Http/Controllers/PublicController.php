<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servicio;
use Illuminate\Routing\Controller;

class PublicController extends Controller
{
    public function create()
    {
        $servicios = Servicio::all();
        return view('agendar', compact('servicios'));
    }
}
