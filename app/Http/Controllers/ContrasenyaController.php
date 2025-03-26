<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Usuari;
use Illuminate\Support\Facades\Hash;

class ContrasenyaController extends Controller
{
    public function mostrarFormulari()
    {
        if (!Session::has('usuari')) {
            return redirect('/login');
        }
        
        return view('modificar_contrasenya');
    }
    
    public function actualitzarContrasenya(Request $request)
    {
        if (!Session::has('usuari')) {
            return redirect('/login');
        }
        
        $usuari = Session::get('usuari');
        $passant = $request->input('passant');
        $passnova = $request->input('passnova');
        $rptpass = $request->input('rptpass');
        
        $errors = [];

        // Obtenir l'usuari de la base de dades
        $usuariBD = Usuari::where('usuari', $usuari)->first();

        // Verifiquem que la contrasenya antiga sigui correcta
        if (!$usuariBD || !Hash::check($passant, $usuariBD->contrasenya)) {
            $errors[] = "La contrasenya antiga no és correcta.";
        }

        // Verificar que la nova contraseña y la confirmació coincideixin
        if ($passnova !== $rptpass) {
            $errors[] = "La contrasenya nova i la confirmació no coincideixen.";
        }

        // Contrasenya segura
        if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $passnova)) {
            $errors[] = "La contrasenya ha de contenir 8 caràcters, una majúscula, un número i un símbol.";
        }

        // Si no hi ha errors, actualitzem la contrasenya
        if (empty($errors)) {
            $usuariBD->contrasenya = Hash::make($passnova);
            
            if ($usuariBD->save()) {
                Session::flash('missatge_exit', "Contrasenya actualitzada correctament.");
            } else {
                Session::flash('missatge', "Error en actualitzar la contrasenya. Torna-ho a intentar.");
            }
        } else {
            Session::flash('missatge', implode('<br>', $errors));
        }

        return redirect('/modificar_contrasenya');
    }
}