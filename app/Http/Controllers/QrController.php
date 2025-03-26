<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\ArticleCompartit;
use Zxing\QrReader;

class QrController extends Controller
{
    public function processarQr(Request $request)
    {
        if (!Session::has('user_id')) {
            return redirect('/login');
        }
        
        // Validar archivo
        $request->validate([
            'qr_file' => 'required|file|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        if ($request->hasFile('qr_file')) {
            $qrFile = $request->file('qr_file');
            
            // Leer el código QR
            $qrContent = $this->leerQR($qrFile->getPathname());
            
            if ($qrContent && isset($qrContent['titol']) && isset($qrContent['cos'])) {
                // Insertar artículo compartido
                $usuariId = Session::get('user_id');
                
                $articleCompartit = new ArticleCompartit();
                $articleCompartit->titol = $qrContent['titol'];
                $articleCompartit->cos = $qrContent['cos'];
                $articleCompartit->usuari_id = $usuariId;
                $articleCompartit->font_origen = 'QR';
                $articleCompartit->data_compartit = now();
                $articleCompartit->article_id = 0; // Añade esta línea, usando 0 como valor predeterminado
                
                if ($articleCompartit->save()) {
                    Session::flash('missatge_exit', "Artículo añadido correctamente desde el QR.");
                } else {
                    Session::flash('missatge', "Error al añadir el artículo.");
                }
            } else {
                Session::flash('missatge', "QR inválido: no contiene los campos requeridos.");
            }
        } else {
            Session::flash('missatge', "Archivo no válido.");
        }
        
        return redirect('/vistaAjax');
    }
    
    public function mostrarFormulari()
    {
        if (!Session::has('user_id')) {
            return redirect('/login');
        }
        
        return view('lectura_qr');
    }
    
    private function leerQR($filePath)
    {
        try {
            // Crear una instancia del lector de QR
            $reader = new QrReader($filePath);
            
            // Leer el código QR
            $decodedData = $reader->text();
            
            // Verificar si se logró leer el código QR
            if ($decodedData !== null) {
                // Decodificar los datos (asumimos que están en formato JSON)
                $data = json_decode($decodedData, true);
                
                // Comprobar si el QR contiene los campos 'titol' y 'cos'
                if (isset($data['titol']) && isset($data['cos'])) {
                    return [
                        'titol' => $data['titol'],
                        'cos' => $data['cos']
                    ];
                }
            }
            
            return null;
        } catch (\Exception $e) {
            // Log del error
            return null;
        }
    }
}