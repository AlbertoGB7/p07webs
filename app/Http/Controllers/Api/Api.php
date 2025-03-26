<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ApiLecturaController extends Controller
{
    public function mostrarFormulari()
    {
        if (!Session::has('user_id')) {
            return redirect('/login');
        }
        
        return view('lectura_api');
    }
}