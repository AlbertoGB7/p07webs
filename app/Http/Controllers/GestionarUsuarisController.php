<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuari;
use App\Models\Article;
use Illuminate\Support\Facades\Session;

class GestionarUsuarisController extends Controller
{
    /*
     * Mostra la llista d'usuaris (només accessible per administradors)
     */
    public function mostrarUsuaris()
    {
        // Comprova que l'usuari sigui administrador
        if (Session::get('rol') !== 'admin') {
            return redirect('/index_usuari');
        }
        
        // Obté tots els usuaris
        $usuaris = Usuari::all();
        
        return view('gestionar_usuaris', ['usuaris' => $usuaris]);
    }
    
    /*
     * Elimina un usuari i els seus articles (només accessible per administradors)
     */
    public function eliminarUsuari(Request $request)
    {
        // Comprova que l'usuari sigui administrador
        if (Session::get('rol') !== 'admin') {
            return redirect('/login');
        }
        
        if ($request->has('id')) {
            $id = (int) $request->input('id');
            
            // Cerca l'usuari
            $usuari = Usuari::find($id);
            
            if ($usuari) {
                // Elimina els articles associats a l'usuari
                Article::where('usuari_id', $id)->delete();
                
                // Elimina l'usuari
                $usuari->delete();
                
                return redirect('/gestionar_usuaris')
                    ->with('missatge_exit', 'Usuari eliminat correctament.');
            }
        }
        
        return redirect('/gestionar_usuaris');
    }
}