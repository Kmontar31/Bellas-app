<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfesionalController extends Controller
{
    public function index (){
        return view(view:'admin.profesionales.index');
    }
}
