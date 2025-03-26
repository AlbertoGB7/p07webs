<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Article;
use App\Http\Controllers\verificar_sessio;

class ModificarController extends Controller
{
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