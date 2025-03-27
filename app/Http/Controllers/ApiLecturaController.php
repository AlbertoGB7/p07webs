<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use GuzzleHttp\Client;

class ApiLecturaController extends Controller
{
    private $apiKey;
    private $client;

    public function __construct()
    {
        $this->apiKey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImtpZCI6IjI4YTMxOGY3LTAwMDAtYTFlYi03ZmExLTJjNzQzM2M2Y2NhNSJ9.eyJpc3MiOiJzdXBlcmNlbGwiLCJhdWQiOiJzdXBlcmNlbGw6Z2FtZWFwaSIsImp0aSI6ImU1NThmNzk1LWM2ZmItNDAzMS04ODRmLWM2NTQ1NDM2MjI4ZCIsImlhdCI6MTczODM0NzA5MSwic3ViIjoiZGV2ZWxvcGVyLzVmOGQ3MDhiLTlhYWQtMjhhOS01YzMwLWUzYjM1MjJlYzkyMiIsInNjb3BlcyI6WyJjbGFzaCJdLCJsaW1pdHMiOlt7InRpZXIiOiJkZXZlbG9wZXIvc2lsdmVyIiwidHlwZSI6InRocm90dGxpbmcifSx7ImNpZHJzIjpbIjk0LjEyNS45Ni4xMzciXSwidHlwZSI6ImNsaWVudCJ9XX0.yZmurWaTh1oHFPDkOOOZorUTnzPvYimFsoIjbaxb0Fpy9WFrTM3JmlZEbSoH1M9auFdwJlkA-KC9mk41Lq6fnw';
        $this->client = new Client();
    }

    /**
     * Mostra el formulari per llegir dades des de l'API
     */
    public function mostrarFormulari()
    {
        // Comprovem que l'usuari estigui autenticat
        if (!Session::has('user_id')) {
            return redirect('/login');
        }
        
        // Tags per defecte
        $clanTag = '#2VL90JP0';
        $playerTag = '#RJPYYL0V';
        
        // Obtenir informació
        $clanInfo = $this->obtenerInfoClan($clanTag);
        $playerInfo = $this->obtenerInfoJugador($playerTag);
        
        return view('lectura_api', [
            'clanInfo' => $clanInfo,
            'playerInfo' => $playerInfo
        ]);
    }
    
    /**
     * Fer sol·licitud a l'API de Clash of Clans
     */
    private function hacerSolicitud($url)
    {
        try {
            $response = $this->client->get($url, [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->apiKey
                ]
            ]);
            
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return null;
        }
    }
    
    /**
     * Obtenir informació d'un clan
     */
    public function obtenerInfoClan($clanTag)
    {
        $url = "https://api.clashofclans.com/v1/clans/" . urlencode($clanTag);
        return $this->hacerSolicitud($url);
    }
    
    /**
     * Obtenir informació d'un jugador
     */
    public function obtenerInfoJugador($playerTag)
    {
        $url = "https://api.clashofclans.com/v1/players/" . urlencode($playerTag);
        return $this->hacerSolicitud($url);
    }
}