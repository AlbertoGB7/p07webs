<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Models\Article;

class QrGeneratorController extends Controller
{
    /*
     * Genera i descarrega un codi QR per a un article específic
     */
    public function generarQr(Request $request)
    {
        // Verificar si existeix l'ID
        if (!$request->has('id')) {
            return response('No s\'han rebut els paràmetres necessaris.', 400);
        }
        
        $id = $request->input('id');
        
        // Obtenir l'article de la base de dades
        $article = Article::find($id);
        
        if (!$article) {
            return response('No s\'ha trobat l\'article.', 404);
        }
        
        // Crear un JSON amb les dades
        $contenido = json_encode([
            'titol' => $article->titol,
            'cos' => $article->cos
        ]);
        
        // Crear l'objecte QR
        $qr_Code = new QrCode($contenido);
        $qr_Code->setSize(300);
        
        // Crear l'escriptor PNG
        $writer = new PngWriter();
        $qrImage = $writer->write($qr_Code);
        
        // Definir el nom de l'arxiu per a la descàrrega
        $file_name = md5(uniqid()) . '.png';
        
        // Retornar la imatge com a resposta per descarregar
        return response($qrImage->getString())
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $file_name . '"');
    }
}