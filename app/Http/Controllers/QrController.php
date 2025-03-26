<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\ArticleCompartit;
use Zxing\QrReader;

class QrController extends Controller
{
    /*
     * Processa la imatge amb el codi QR pujat per l'usuari
     */
    public function processarQr(Request $request)
    {
        if (!Session::has('user_id')) {
            return redirect('/login');
        }
        
        // Validar arxiu
        $request->validate([
            'qr_file' => 'required|file|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        if ($request->hasFile('qr_file')) {
            $qrFile = $request->file('qr_file');
            
            // Llegir el codi QR
            $qrContent = $this->leerQR($qrFile->getPathname());
            
            if ($qrContent && isset($qrContent['titol']) && isset($qrContent['cos'])) {
                // Insertar article compartit
                $usuariId = Session::get('user_id');
                
                $articleCompartit = new ArticleCompartit();
                $articleCompartit->titol = $qrContent['titol'];
                $articleCompartit->cos = $qrContent['cos'];
                $articleCompartit->usuari_id = $usuariId;
                $articleCompartit->font_origen = 'QR';
                $articleCompartit->data_compartit = now();
                $articleCompartit->article_id = 0; // Afegeix aquesta línia, usant 0 com a valor predeterminat
                
                if ($articleCompartit->save()) {
                    Session::flash('missatge_exit', "Article afegit correctament des del QR.");
                } else {
                    Session::flash('missatge', "Error a l'afegir l'article.");
                }
            } else {
                Session::flash('missatge', "QR invàlid: no conté els camps requerits.");
            }
        } else {
            Session::flash('missatge', "Arxiu no vàlid.");
        }
        
        return redirect('/vistaAjax');
    }
    
    /*
     * Mostra el formulari per pujar un codi QR
     */
    public function mostrarFormulari()
    {
        if (!Session::has('user_id')) {
            return redirect('/login');
        }
        
        return view('lectura_qr');
    }
    
    /*
     * Llegeix un codi QR i retorna les dades en format array
     */
    private function leerQR($filePath)
    {
        try {
            // Crear una instància del lector de QR
            $reader = new QrReader($filePath);
            
            // Llegir el codi QR
            $decodedData = $reader->text();
            
            // Verificar si s'ha aconseguit llegir el codi QR
            if ($decodedData !== null) {
                // Descodificar les dades (suposem que estan en format JSON)
                $data = json_decode($decodedData, true);
                
                // Comprovar si el QR conté els camps 'titol' i 'cos'
                if (isset($data['titol']) && isset($data['cos'])) {
                    return [
                        'titol' => $data['titol'],
                        'cos' => $data['cos']
                    ];
                }
            }
            
            return null;
        } catch (\Exception $e) {
            // Log de l'error
            return null;
        }
    }
}