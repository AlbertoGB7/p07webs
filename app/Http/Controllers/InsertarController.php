<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Article;

class InsertarController extends Controller
{
    /*
     * Mostra el formulari per insertar articles
     */
    public function mostrarFormulari()
    {
        return view('insertar');
    }

    /*
     * Processa les dades del formulari i inserta l'article
     */
    public function insertar(Request $request)
    {
        $titol = trim($request->input('titol'));
        $cos = trim($request->input('cos'));
        $errors = [];

        // Validació de camps obligatoris
        if (empty($titol)) {
            $errors[] = "El camp 'Títol' és obligatori.";
        }
        if (empty($cos)) {
            $errors[] = "El camp 'Cos' és obligatori.";
        }

        if (!empty($errors)) {
            Session::flash('missatge', implode('<br>', $errors));
            Session::flash('titol', $titol);
            Session::flash('cos', $cos);
            return redirect('/insertar');
        }

        // Verifica si ja existeix un article idèntic
        $existeix = Article::where('titol', $titol)
                          ->where('cos', $cos)
                          ->exists();

        if ($existeix) {
            Session::flash('missatge', "L'article introduit ja existeix.");
            Session::flash('titol', $titol);
            Session::flash('cos', $cos);
            return redirect('/insertar');
        }

        // Crea un nou article
        $article = new Article();
        $article->titol = $titol;
        $article->cos = $cos;
        $article->usuari_id = Session::get('user_id');

        if ($article->save()) {
            Session::flash('missatge_exit', "Article insertat correctament");
            return redirect('/insertar');
        } else {
            Session::flash('missatge', "Error en inserir l'article.");
            return redirect('/insertar');
        }
    }
}