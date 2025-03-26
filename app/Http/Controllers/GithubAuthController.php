<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

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
        // Configuració de Hybridauth
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
    
    /*
     * Redirigeix a GitHub per iniciar l'autenticació
     */
    public function redirectToGithub()
    {
        try {
            $hybridauth = new Hybridauth($this->config);
            $adapter = $hybridauth->authenticate('GitHub');
            
            // Si arribem aquí, l'autenticació ja està completada
            // (normalment no passarà a la primera crida)
            return $this->handleGithubCallback();
        } catch (\Exception $e) {
            return redirect('/login')->with('missatge', 'Error amb l\'autenticació de GitHub: ' . $e->getMessage());
        }
    }
    
    /*
     * Gestiona la resposta de GitHub després de l'autenticació
     */
    public function handleGithubCallback()
    {
        try {
            $hybridauth = new Hybridauth($this->config);
            $adapter = $hybridauth->authenticate('GitHub');
            
            // Obtenir el perfil de l'usuari
            $userProfile = $adapter->getUserProfile();
            
            // Dades de l'usuari
            $email = $userProfile->email;
            $name = $userProfile->firstName . ' ' . $userProfile->lastName;
            
            if (empty($email)) {
                // Intenta obtenir el correu electrònic d'una altra manera si no està disponible directament
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
                return redirect('/login')->with('missatge', 'No s\'ha pogut obtenir el correu de l\'usuari. Si us plau, permet l\'accés al correu al teu compte de GitHub.');
            }
            
            // Comprova si l'usuari ja existeix a la base de dades
            $existingUser = Usuari::obtenirPerCorreu($email);
            
            if (!$existingUser) {
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
                Session::put('usuari', $existingUser->usuari);
                Session::put('user_id', $existingUser->id);
                Session::put('rol', $existingUser->rol);
            }
            
            // Establir la sessió com a login exitós
            Session::put('login_exitoso', true);
            
            // Desconnectar l'usuari de GitHub
            $adapter->disconnect();
            
            return redirect('/index_usuari');
        } catch (\Exception $e) {
            return redirect('/login')->with('missatge', 'Error amb l\'autenticació de GitHub: ' . $e->getMessage());
        }
    }
}