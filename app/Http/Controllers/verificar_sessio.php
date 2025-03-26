<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class verificar_sessio
{
    /*
     * Verifica si la sessió ha caducat pel temps d'inactivitat
     */
    public static function verificar()
    {
        $maxDuracioSessio = 40 * 60; // 40 minuts

        if (Session::has('usuari')) {
            // Si no hi ha temps d'inici, l'establim
            if (!Session::has('start_time')) {
                Session::put('start_time', time());
            }

            // Comprovem si ha passat el temps màxim
            if (time() - Session::get('start_time') > $maxDuracioSessio) {
                Session::flush(); // Netejem la sessió
                return redirect('/logout');
            } else {
                Session::put('start_time', time()); // Actualitzem el temps
            }
        } else {
            return redirect('/login');
        }

        return null; // tot bé
    }
}