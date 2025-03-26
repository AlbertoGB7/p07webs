<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuari;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class WelcomeController extends Controller
{
    /*
     * Mostra la pàgina de benvinguda i verifica la sessió per cookie
     */
    public function index()
    {
        // Comprovar si ja hi ha una sessió activa
        if (Session::has('usuari') && Session::has('user_id')) {
            return redirect('/index_usuari');
        }

        // Comprovar si existeix la cookie 'remember_me_token'
        if (Cookie::has('remember_me_token')) {
            $token = Cookie::get('remember_me_token');

            // Buscar un usuari basat en el token
            $user = Usuari::obtenirPerToken($token);

            if ($user) {
                // Login automàtic exitós: establir sessió
                Session::put('usuari', $user->usuari);
                Session::put('user_id', $user->id);
                Session::put('rol', $user->rol);

                // Regenerar el token per més seguretat
                $nuevoToken = Str::random(32);
                $user->guardarTokenRemember($nuevoToken); // Actualitzar a la base de dades
                
                // Establir nova cookie (30 dies)
                Cookie::queue('remember_me_token', $nuevoToken, 60 * 24 * 30);

                return redirect('/index_usuari');
            }
        }

        // Si hi ha un missatge de logout, es passarà a la vista
        $mensajeLogout = Session::get('missatge_logout');
        
        return view('welcome', ['mensajeLogout' => $mensajeLogout]);
    }
}