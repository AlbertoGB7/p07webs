<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Usuari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MostrarController extends Controller
{
    /*
     * Mostra la llista d'articles públics amb paginació
     */
    public function index(Request $request)
    {
        // Variables inicials
        $terme_cerca = $request->input('terme', '');
        $orden = $request->input('orden', 'data_desc');
        $articles_per_pagina = (int)$request->input('articles_per_pagina', 5);
        $pagina_actual = (int)$request->input('pagina', 1);

        // Si intenten posar una pàgina inferior a 1, redirigim
        if ($pagina_actual < 1) {
            return redirect()->route('mostrar', ['pagina' => 1]);
        }

        // Consulta base d'articles
        $query = Article::query();

        // Si hi ha terme de cerca
        if ($terme_cerca !== '') {
            $query->where('titol', 'like', "%{$terme_cerca}%")
                  ->orWhere('cos', 'like', "%{$terme_cerca}%");
        }

        // Ordenació
        switch ($orden) {
            case 'titol_asc':
                $query->orderBy('titol', 'asc');
                break;
            case 'titol_desc':
                $query->orderBy('titol', 'desc');
                break;
            case 'data_asc':
                $query->orderBy('data', 'asc');
                break;
            default: // data_desc
                $query->orderBy('data', 'desc');
                break;
        }

        // Calcular total d'articles i pàgines
        $total_articles = $query->count();
        $total_pagines = ceil($total_articles / $articles_per_pagina);

        // Si intenten accedir a una pàgina que no existeix
        if ($pagina_actual > $total_pagines && $total_pagines > 0) {
            return redirect()->route('mostrar', ['pagina' => 1]);
        }

        // Obtenir resultats amb paginació
        $resultats = $query->skip(($pagina_actual - 1) * $articles_per_pagina)
                         ->take($articles_per_pagina)
                         ->get();

        return view('mostrar', [
            'resultats' => $resultats,
            'pagina_actual' => $pagina_actual,
            'total_pagines' => $total_pagines,
            'articles_per_pagina' => $articles_per_pagina,
            'orden' => $orden,
            'terme_cerca' => $terme_cerca,
            'total_articles' => $total_articles
        ]);
    }

    /*
     * Mostra el formulari per seleccionar l'article a modificar
     */
    public function mostrarFormulari()
    {
        return view('modificar');
    }

    /*
     * Processa el formulari per modificar un article
     */
    public function processarFormulari(Request $request)
    {
        $id = trim($request->input('id'));
        $field = $request->input('field');
        $new_value = $request->input('new_value');
        $user_id = Session::get('user_id');
        $errors = [];

        // Verifiquem que l'ID no estigui buit
        if (empty($id)) {
            $errors[] = "El camp 'ID' és obligatori.";
            Session::forget('id');
        } else {
            // Verifiquem que sigui un número
            if (!is_numeric($id)) {
                $errors[] = "El camp 'ID' no pot contenir lletres, només números.";
                Session::forget('id');
            } else {
                Session::put('id', $id);
            }
        }

        // Si el camp new_value està definit i està buit, afegim error
        if ($request->has('new_value') && empty(trim($new_value))) {
            if ($field === 'titol') {
                $errors[] = "El camp 'Títol' és obligatori.";
            } else if ($field === 'cos') {
                $errors[] = "El camp 'Cos' és obligatori.";
            }
        }

        // Si hi ha errors, els guardem i redirigim
        if (!empty($errors)) {
            Session::flash('missatge', implode("<br>", $errors));
            return redirect('/modificar');
        }

        // Si no hi ha errors, però tampoc hi ha valor nou, mostrem l'article
        if ($id && $field && !$request->has('new_value')) {
            $article = Article::where('ID', $id)
                              ->where('usuari_id', $user_id)
                              ->first();

            if ($article) {
                Session::forget('id');
                return view('modificar_resultat', [
                    'article' => $article,
                    'field' => $field
                ]);
            } else {
                Session::flash('missatge', "L'article no ha sigut trobat o no en tens permís.");
                Session::forget('id');
                return redirect('/modificar');
            }
        }

        // Si hi ha valor nou, fem l'actualització
        if ($id && $field && $request->has('new_value')) {
            $article = Article::where('ID', $id)
                              ->where('usuari_id', $user_id)
                              ->first();

            if ($article) {
                // Actualitzem l'article
                if ($field === 'titol') {
                    $article->titol = $new_value;
                } else if ($field === 'cos') {
                    $article->cos = $new_value;
                }

                if ($article->save()) {
                    Session::flash('missatge_exit', "Article modificat correctament.");
                } else {
                    Session::flash('missatge', "Error en modificar l'article.");
                }
                
                return redirect('/modificar');
            } else {
                Session::flash('missatge', "L'article no ha sigut trobat o no en tens permís.");
                return redirect('/modificar');
            }
        }

        return redirect('/modificar');
    }
}