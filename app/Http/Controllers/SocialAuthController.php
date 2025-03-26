<?php


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
        $this->googleClient = new Google_Client();
        $this->googleClient->setClientId(config('services.google.client_id'));
        $this->googleClient->setClientSecret(config('services.google.client_secret'));
        $this->googleClient->setRedirectUri(config('services.google.redirect'));
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }

    public function redirectToGoogle()
    {
        // Generar URL para redireccionar a Google
        $authUrl = $this->googleClient->createAuthUrl();
        return redirect($authUrl);
    }

    public function handleGoogleCallback()
    {
        try {
            if (request()->has('code')) {
                // Obtener el token de acceso
                $token = $this->googleClient->fetchAccessTokenWithAuthCode(request('code'));
                $this->googleClient->setAccessToken($token['access_token']);
                
                // Obtener información del usuario
                $googleService = new Google_Service_Oauth2($this->googleClient);
                $googleUser = $googleService->userinfo->get();
                
                $email = $googleUser->email;
                $nombreCompleto = $googleUser->name;
                
                // Buscar el usuario por su correo
                $usuarioExistente = Usuari::obtenirPerCorreu($email);
                
                if (!$usuarioExistente) {
                    // Si el usuario no existe, lo creamos
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
                    // Si el usuario ya existe, iniciamos su sesión
                    Session::put('usuari', $usuarioExistente->usuari);
                    Session::put('user_id', $usuarioExistente->id);
                    Session::put('rol', $usuarioExistente->rol);
                }
                
                // Establecer la sesión como login exitoso
                Session::put('login_exitoso', true);
                
                return redirect('/index_usuari');
            } else {
                return redirect('/login')->with('missatge', 'Error durante la autenticación con Google');
            }
        } catch (\Exception $e) {
            return redirect('/login')->with('missatge', 'Error durante la autenticación: ' . $e->getMessage());
        }
    }
}