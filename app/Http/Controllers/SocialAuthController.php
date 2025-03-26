<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Http\Controllers;

use Google_Client;
use Google_Service_Oauth2;
use App\Models\Usuari;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SocialAuthController extends Controller
{
    protected $googleClient;

    public function __construct()
    {
        // Configuració del client de Google
        $this->googleClient = new Google_Client();
        $this->googleClient->setClientId(config('services.google.client_id'));
        $this->googleClient->setClientSecret(config('services.google.client_secret'));
        $this->googleClient->setRedirectUri(config('services.google.redirect'));
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }

    /*
     * Redirigeix a Google per iniciar l'autenticació
     */
    public function redirectToGoogle()
    {
        // Generar URL per redirigir a Google
        $authUrl = $this->googleClient->createAuthUrl();
        return redirect($authUrl);
    }

    /*
     * Gestiona la resposta de Google després de l'autenticació
     */
    public function handleGoogleCallback()
    {
        try {
            if (request()->has('code')) {
                // Obtenir el token d'accés
                $token = $this->googleClient->fetchAccessTokenWithAuthCode(request('code'));
                $this->googleClient->setAccessToken($token['access_token']);
                
                // Obtenir informació de l'usuari
                $googleService = new Google_Service_Oauth2($this->googleClient);
                $googleUser = $googleService->userinfo->get();
                
                $email = $googleUser->email;
                $nombreCompleto = $googleUser->name;
                
                // Buscar l'usuari pel seu correu
                $usuarioExistente = Usuari::obtenirPerCorreu($email);
                
                if (!$usuarioExistente) {
                    // Si l'usuari no existeix, el creem
                    $nombreAleatorio = 'Usuari' . rand(1000, 9999);
                    
                    $usuario = new Usuari();
                    $usuario->usuari = $nombreAleatorio;
                    $usuario->correo = $email;
                    $usuario->aut_social = 'si';
                    $usuario->rol = 'user';
                    $usuario->save();
                    
                    Session::put('usuari', $nombreAleatorio);
                    Session::put('user_id', $usuario->id);
                    Session::put('rol', $usuario->rol);
                } else {
                    // Si l'usuari ja existeix, iniciem la seva sessió
                    Session::put('usuari', $usuarioExistente->usuari);
                    Session::put('user_id', $usuarioExistente->id);
                    Session::put('rol', $usuarioExistente->rol);
                }
                
                // Establir la sessió com a login exitós
                Session::put('login_exitoso', true);
                
                return redirect('/index_usuari');
            } else {
                return redirect('/login')->with('missatge', 'Error durant l\'autenticació amb Google');
            }
        } catch (\Exception $e) {
            return redirect('/login')->with('missatge', 'Error durant l\'autenticació: ' . $e->getMessage());
        }
    }
}