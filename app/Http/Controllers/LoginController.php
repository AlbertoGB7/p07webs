<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuari;

class LoginController extends Controller
{
    /*
     * Mostra la vista de login
     */
    public function mostrarLogin()
    {
        return view('login_nou');
    }

    /*
     * Verifica les credencials de l'usuari
     */
    public function verificarLogin(Request $request)
    {
        $request->flash(); // per mantenir els valors d'entrada
        $errors = [];

        // Si ja hi ha una cookie per recordar l'usuari
        if ($request->cookie('remember_me_token')) {
            $token = $request->cookie('remember_me_token');
            $user = Usuari::where('token_remember', $token)
                          ->where('token_remember_expiracio', '>', now())
                          ->first();

            if ($user) {
                session([
                    'usuari' => $user->usuari,
                    'user_id' => $user->id,
                    'rol' => $user->rol,
                    'start_time' => time(),
                ]);
                return redirect('/index_usuari');
            }
        }

        // Comprovació normal per formulari
        $usuari = $request->input('usuari');
        $email = $request->input('email');
        $password = $request->input('pass');
        $recordar = $request->has('recordar');

        if (empty($usuari)) $errors[] = "El camp 'Usuari' és obligatori.";
        if (empty($password)) $errors[] = "El camp 'Contrasenya' és obligatori.";

        if (count($errors)) {
            session()->flash('missatge', implode('<br>', $errors));
            session(['intentos_fallidos' => session('intentos_fallidos', 0) + 1]);
            return redirect('/login');
        }

        // Verificar usuari a la base de dades
        $user = Usuari::where('usuari', $usuari)->first();

        if ($user && Hash::check($password, $user->contrasenya)) {
            session([
                'usuari' => $user->usuari,
                'user_id' => $user->id,
                'rol' => $user->rol,
                'start_time' => time(),
                'intentos_fallidos' => 0
            ]);

            Session::put('usuari', $user->usuari);
            Session::put('user_id', $user->id);
            Session::put('rol', $user->rol);
            Session::put('login_exitoso', true);

            if ($recordar) {
                $token = bin2hex(random_bytes(32));
                $expiracio = now()->addDays(7);
                $user->token_remember = $token;
                $user->token_remember_expiracio = $expiracio;
                $user->save();
                Cookie::queue('remember_me_token', $token, 60 * 24 * 7); // 7 dies
            } else {
                Cookie::queue(Cookie::forget('remember_me_token'));
            }

            return redirect('/index_usuari');
        } else {
            session()->flash('missatge', "Usuari o contrasenya incorrectes");
            session(['intentos_fallidos' => session('intentos_fallidos', 0) + 1]);
            return redirect('/login');
        }
    }

    /*
     * Mostra la pàgina principal de l'usuari autenticat
     */
    public function mostrarIndexUsuari()
    {
        if (!session('usuari')) {
            return redirect('/login');
        }

        $usuari = session('usuari');
        return view('index_usuari', compact('usuari'));
    }

    /*
     * Mostra el formulari de registre
     */
    public function mostrarRegistre()
    {
        return view('registre_nou');
    }
}