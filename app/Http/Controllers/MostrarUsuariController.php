<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Article;

class MostrarUsuariController extends Controller
{
    /*
     * Mostra els articles de l'usuari autenticat amb paginació
     */
    public function index(Request $request)
    {
        if (!Session::has('user_id')) {
            return redirect('/login');
        }

        $usuari_id = Session::get('user_id');
        $articles_per_pagina = (int) $request->query('articles_per_pagina', 5);
        $pagina_actual = max(1, (int) $request->query('pagina', 1));
        $terme_cerca = trim($request->query('terme', ''));
        $orden = $request->query('orden', 'data_desc');

        // Consulta base amb Eloquent
        $query = Article::where('usuari_id', $usuari_id);

        if (!empty($terme_cerca)) {
            $query->where('titol', 'like', '%' . $terme_cerca . '%');
        }

        $total_articles = $query->count();
        $total_pagines = ceil($total_articles / $articles_per_pagina);

        if ($pagina_actual > $total_pagines && $total_pagines > 0) {
            return redirect()->route('mostrar_usuari', [
                'pagina' => 1,
                'articles_per_pagina' => $articles_per_pagina,
                'orden' => $orden,
                'terme' => $terme_cerca,
            ]);
        }

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
            default:
                $query->orderBy('data', 'desc');
                break;
        }

        $resultats = $query->skip(($pagina_actual - 1) * $articles_per_pagina)
                           ->take($articles_per_pagina)
                           ->get();

        return view('mostrar_usuari', [
            'resultats' => $resultats,
            'pagina_actual' => $pagina_actual,
            'total_pagines' => $total_pagines,
            'articles_per_pagina' => $articles_per_pagina,
            'orden' => $orden,
            'terme_cerca' => $terme_cerca,
        ]);
    }
}