<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ApiLecturaController extends Controller
{
    /**
     * Mostra el formulari per llegir dades des de l'API
     * 
     */
    public function mostrarFormulari()
    {
        // Comprovem que l'usuari estigui autenticat
        if (!Session::has('user_id')) {
            return redirect('/login');
        }
        
        return view('lectura_api');
    }
}