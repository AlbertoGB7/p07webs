<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class verificar_sessio
{
    public static function verificar()
    {
        $maxDuracioSessio = 40 * 60; // 40 minuts

        if (Session::has('usuari')) {
            if (!Session::has('start_time')) {
                Session::put('start_time', time());
            }

            if (time() - Session::get('start_time') > $maxDuracioSessio) {
                Session::flush(); // Opcional
                return redirect('/logout');
            } else {
                Session::put('start_time', time()); // Actualitza
            }
        } else {
            return redirect('/login');
        }

        return null; // tot bé
    }
}
