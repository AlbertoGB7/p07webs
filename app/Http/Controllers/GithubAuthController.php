<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Usuari;
use Hybridauth\Hybridauth;
use Hybridauth\Exception\Exception;

class GithubAuthController extends Controller
{
    protected $config;
    
    public function __construct()
    {
        // Configuración de Hybridauth
        $this->config = [
            'callback' => config('services.github.redirect'),
            'providers' => [
                'GitHub' => [
                    'enabled' => true,
                    'keys' => [
                        'id' => config('services.github.client_id'),
                        'secret' => config('services.github.client_secret'),
                    ],
                    'scope' => 'user:email',
                ],
            ],
            'debug_mode' => env('APP_DEBUG', false),
            'debug_file' => storage_path('logs/hybridauth.log'),
        ];
    }
    
    public function redirectToGithub()
    {
        try {
            $hybridauth = new Hybridauth($this->config);
            $adapter = $hybridauth->authenticate('GitHub');
            
            // Si llegamos aquí, la autenticación ya está completada
            // (normalmente no sucederá en la primera llamada)
            return $this->handleGithubCallback();
        } catch (\Exception $e) {
            return redirect('/login')->with('missatge', 'Error con la autenticación de GitHub: ' . $e->getMessage());
        }
    }
    
    public function handleGithubCallback()
    {
        try {
            $hybridauth = new Hybridauth($this->config);
            $adapter = $hybridauth->authenticate('GitHub');
            
            // Obtener el perfil del usuario
            $userProfile = $adapter->getUserProfile();
            
            // Datos del usuario
            $email = $userProfile->email;
            $name = $userProfile->firstName . ' ' . $userProfile->lastName;
            
            if (empty($email)) {
                // Intentar obtener el email de otra forma si no está disponible directamente
                $emails = $adapter->apiRequest('user/emails');
                if (!empty($emails) && is_array($emails)) {
                    foreach ($emails as $emailData) {
                        if (isset($emailData->primary) && $emailData->primary && isset($emailData->email)) {
                            $email = $emailData->email;
                            break;
                        }
                    }
                }
            }
            
            if (empty($email)) {
                return redirect('/login')->with('missatge', 'No se pudo obtener el email del usuario. Por favor, permite el acceso al email en tu cuenta de GitHub.');
            }
            
            // Comprobar si el usuario ya existe en la base de datos
            $existingUser = Usuari::obtenirPerCorreu($email);
            
            if (!$existingUser) {
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
                Session::put('usuari', $existingUser->usuari);
                Session::put('user_id', $existingUser->id);
                Session::put('rol', $existingUser->rol);
            }
            
            // Establecer la sesión como login exitoso
            Session::put('login_exitoso', true);
            
            // Desconectar al usuario de GitHub
            $adapter->disconnect();
            
            return redirect('/index_usuari');
        } catch (\Exception $e) {
            return redirect('/login')->with('missatge', 'Error con la autenticación de GitHub: ' . $e->getMessage());
        }
    }
}