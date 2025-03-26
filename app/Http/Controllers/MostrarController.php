<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Usuari;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MostrarController extends Controller
{
    public function index(Request $request)
    {
        // Variables iniciales
        $terme_cerca = $request->input('terme', '');
        $orden = $request->input('orden', 'data_desc');
        $articles_per_pagina = (int)$request->input('articles_per_pagina', 5);
        $pagina_actual = (int)$request->input('pagina', 1);

        // Si intentan poner una página inferior a 1, redirige
        if ($pagina_actual < 1) {
            return redirect()->route('mostrar', ['pagina' => 1]);
        }

        // Consulta base de artículos
        $query = Article::query();

        // Si hay término de búsqueda
        if ($terme_cerca !== '') {
            $query->where('titol', 'like', "%{$terme_cerca}%")
                  ->orWhere('cos', 'like', "%{$terme_cerca}%");
        }

        // Ordenación
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

        // Calcular total de artículos y páginas
        $total_articles = $query->count();
        $total_pagines = ceil($total_articles / $articles_per_pagina);

        // Si intentan acceder a una página que no existe
        if ($pagina_actual > $total_pagines && $total_pagines > 0) {
            return redirect()->route('mostrar', ['pagina' => 1]);
        }

        // Obtener resultados con paginación
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
}