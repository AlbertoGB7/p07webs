<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use App\Models\Article;

class QrGeneratorController extends Controller
{
    public function generarQr(Request $request)
    {
        // Verificar si existe el ID
        if (!$request->has('id')) {
            return response('No se han recibido los parámetros necesarios.', 400);
        }
        
        $id = $request->input('id');
        
        // Obtener el artículo de la base de datos
        $article = Article::find($id);
        
        if (!$article) {
            return response('No se encontró el artículo.', 404);
        }
        
        // Crear un JSON con los datos
        $contenido = json_encode([
            'titol' => $article->titol,
            'cos' => $article->cos
        ]);
        
        // Crear el objeto QR
        $qr_Code = new QrCode($contenido);
        $qr_Code->setSize(300);
        
        // Crear el escritor PNG
        $writer = new PngWriter();
        $qrImage = $writer->write($qr_Code);
        
        // Definir el nombre del archivo para la descarga
        $file_name = md5(uniqid()) . '.png';
        
        // Devolver la imagen como respuesta para descargar
        return response($qrImage->getString())
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $file_name . '"');
    }
}