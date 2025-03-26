<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Article;
use App\Models\ArticleCompartit;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function mostrarArticlesCompartits()
    {
        return view('vistaAjax');
    }
    
    public function mostrarCopiarArticle(Request $request)
    {
        $article_id = intval($request->query('article_id'));
        
        $article = ArticleCompartit::find($article_id);
        
        if (!$article) {
            return redirect('/vistaAjax')->with('missatge', "No s'ha trobat l'article.");
        }
        
        return view('copiarAjax', ['article' => $article]);
    }
    
    public function controladorAjax(Request $request)
    {
        if ($request->query('action') === 'obtenir_articles') {
            $articles = ArticleCompartit::select(
                'articles_compartits.*',
                'usuaris.usuari as usuari'
            )
            ->join('usuaris', 'articles_compartits.usuari_id', '=', 'usuaris.ID')
            ->get();
            
            return response()->json($articles);
        }
        
        if ($request->query('action') === 'compartir_article' && $request->has('article_id')) {
            $article_id = intval($request->query('article_id'));
            $usuari_id = Session::get('user_id');
            
            // Verifica que el usuario es propietario del artículo
            $article = Article::where('ID', $article_id)
                             ->where('usuari_id', $usuari_id)
                             ->first();
                             
            if ($article) {
                // Comprueba si el artículo ya ha sido compartido
                $yaCompartido = ArticleCompartit::where('article_id', $article_id)->exists();
                
                if (!$yaCompartido) {
                    // Comparte el artículo
                    $articleCompartit = new ArticleCompartit();
                    $articleCompartit->usuari_id = $usuari_id;
                    $articleCompartit->titol = $article->titol;
                    $articleCompartit->cos = $article->cos;
                    $articleCompartit->data_compartit = now();
                    $articleCompartit->article_id = $article_id;
                    $articleCompartit->font_origen = 'base_dades';
                    
                    if ($articleCompartit->save()) {
                        Session::flash('missatge_exit', "Article compartit exitosament!");
                    } else {
                        Session::flash('missatge', "Hi ha hagut un error al compartir l'article.");
                    }
                } else {
                    Session::flash('missatge', "Aquest article ja ha sigut compartit.");
                }
            } else {
                Session::flash('missatge', "No tens permís per compartir aquest article.");
            }
            
            return redirect('/mostrar_usuari');
        }
        
        if ($request->input('action') === 'copiar_article') {
            $article_id = intval($request->input('article_id'));
            $titol = trim($request->input('titol'));
            $cos = trim($request->input('cos'));
            $usuari_id = Session::get('user_id');
            
            // Obtener el artículo compartido
            $articleCompartit = ArticleCompartit::find($article_id);
            
            if ($articleCompartit) {
                // Crear un nuevo artículo para el usuario actual
                $nouArticle = new Article();
                $nouArticle->titol = $titol;
                $nouArticle->cos = $cos;
                $nouArticle->usuari_id = $usuari_id;
                $nouArticle->data = now();
                
                if ($nouArticle->save()) {
                    // Eliminar el artículo de la tabla "articles_compartits" después de copiarlo
                    if ($articleCompartit->delete()) {
                        Session::flash('missatge_exit', "Article copiat i eliminat correctament!");
                    } else {
                        Session::flash('missatge', "Error al eliminar l'article de la taula 'articles_compartits'.");
                    }
                } else {
                    Session::flash('missatge', "Error al copiar l'article.");
                }
            } else {
                Session::flash('missatge', "Article no trobat.");
            }
            
            return redirect('/vistaAjax');
        }
        
        return redirect('/index_usuari');
    }
}