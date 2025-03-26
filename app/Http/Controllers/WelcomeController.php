<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuari;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class WelcomeController extends Controller
{
    public function index()
    {
        // Comprobar si ya hay una sesión activa
        if (Session::has('usuari') && Session::has('user_id')) {
            return redirect('/index_usuari');
        }

        // Comprobar si existe la cookie 'remember_me_token'
        if (Cookie::has('remember_me_token')) {
            $token = Cookie::get('remember_me_token');

            // Buscar un usuario basado en el token
            $user = Usuari::obtenirPerToken($token);

            if ($user) {
                // Login automático exitoso: establecer sesión
                Session::put('usuari', $user->usuari);
                Session::put('user_id', $user->id);
                Session::put('rol', $user->rol);

                // Regenerar el token para mayor seguridad
                $nuevoToken = Str::random(32);
                $user->guardarTokenRemember($nuevoToken); // Actualizar en la base de datos
                
                // Establecer nueva cookie (30 días)
                Cookie::queue('remember_me_token', $nuevoToken, 60 * 24 * 30);

                return redirect('/index_usuari');
            }
        }

        // Si hay un mensaje de logout, se pasará a la vista
        $mensajeLogout = Session::get('missatge_logout');
        
        return view('welcome', ['mensajeLogout' => $mensajeLogout]);
    }
}