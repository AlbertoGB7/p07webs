<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuari;
use App\Models\Article;
use Illuminate\Support\Facades\Session;

class GestionarUsuarisController extends Controller
{
    public function mostrarUsuaris()
    {
        // Verificar que el usuario sea administrador
        if (Session::get('rol') !== 'admin') {
            return redirect('/index_usuari');
        }
        
        // Obtener todos los usuarios
        $usuaris = Usuari::all();
        
        return view('gestionar_usuaris', ['usuaris' => $usuaris]);
    }
    
    public function eliminarUsuari(Request $request)
    {
        // Verificar que el usuario sea administrador
        if (Session::get('rol') !== 'admin') {
            return redirect('/login');
        }
        
        if ($request->has('id')) {
            $id = (int) $request->input('id');
            
            // Buscar el usuario
            $usuari = Usuari::find($id);
            
            if ($usuari) {
                // Eliminar artículos asociados al usuario
                Article::where('usuari_id', $id)->delete();
                
                // Eliminar el usuario
                $usuari->delete();
                
                return redirect('/gestionar_usuaris')
                    ->with('missatge_exit', 'Usuari eliminat correctament.');
            }
        }
        
        return redirect('/gestionar_usuaris');
    }
}