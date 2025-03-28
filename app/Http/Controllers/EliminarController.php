<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

class EliminarController extends Controller
{
    /*
     * Mostra el formulari per eliminar articles
     */
    public function mostrarFormulari()
    {
        return view('eliminar');
    }

    /*
     * Processa la cerca o eliminació d'articles
     */
    public function buscarOEliminar(Request $request)
    {
        $usuari_id = Session::get('user_id');
        $id = trim($request->input('id'));

        // Validació de l'ID
        if (empty($id)) {
            Session::flash('missatge', "El camp 'ID' és obligatori.");
            Session::forget('id');
            return redirect('/eliminar');
        }

        if (!is_numeric($id)) {
            Session::flash('missatge', "El camp 'ID' no pot contenir lletres, només números.");
            Session::forget('id');
            return redirect('/eliminar');
        }

        Session::put('id', $id);

        // Si s'ha premut el botó "Buscar"
        if ($request->has('buscar')) {
            $article = Article::where('ID', $id)->where('usuari_id', $usuari_id)->first();

            if ($article) {
                return view('eliminar_resultat', [
                    'titol' => 'Article:',
                    'article' => $article
                ]);
            } else {
                Session::flash('missatge', "L'article no ha sigut trobat o no ets el propietari.");
                return redirect('/eliminar');
            }
        }

        // Si s'ha premut el botó "Eliminar"
        if ($request->has('eliminar')) {
            $article = Article::where('ID', $id)->where('usuari_id', $usuari_id)->first();

            if ($article) {
                $article->delete();
                Session::forget('id');
                Session::flash('missatge_exit', "Article eliminat correctament");
                return redirect('/eliminar');
            } else {
                Session::flash('missatge', "No pots eliminar aquest article, no ets el propietari.");
                return redirect('/eliminar');
            }
        }

        return redirect('/eliminar');
    }
}