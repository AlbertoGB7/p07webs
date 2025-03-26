<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;

class ArticleApiController extends Controller
{
    /**
     * Obtener artículos con paginación
     * GET /api/articles
     * GET /api/articles/{user_id}
     */
    public function getArticles(Request $request, $userId = null)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);

        $query = Article::query();
        
        if ($userId) {
            $query->where('usuari_id', $userId);
        }
        
        $articles = $query->offset($offset)
                         ->limit($limit)
                         ->get();
        
        return response()->json($articles);
    }

    /**
     * Crear un nuevo artículo
     * POST /api/articles
     */
    public function postArticles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titol' => 'required|string',
            'cos' => 'required|string',
            'usuari_id' => 'required|integer|exists:usuaris,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Dates incompletes'], 400);
        }

        $article = new Article();
        $article->titol = $request->input('titol');
        $article->cos = $request->input('cos');
        $article->usuari_id = $request->input('usuari_id');
        $article->data = now();
        
        $result = $article->save();
        
        return response()->json(['success' => $result]);
    }

    /**
     * Actualizar un artículo existente
     * PUT /api/articles
     */
    public function putArticles(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:articles,ID',
            'usuari_id' => 'required|integer|exists:usuaris,id',
            'nou_valor' => 'required|string',
            'camp' => 'required|string|in:titol,cos'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Dates incompletes'], 400);
        }

        $article = Article::where('ID', $request->input('id'))
                         ->where('usuari_id', $request->input('usuari_id'))
                         ->first();

        if (!$article) {
            return response()->json(['error' => 'No tens permisos per modificar aquest article'], 403);
        }
        
        $campo = $request->input('camp');
        $article->$campo = $request->input('nou_valor');
        $result = $article->save();
        
        return response()->json(['success' => $result]);
    }

    /**
     * Eliminar un artículo
     * DELETE /api/articles/{id}/{user_id}
     */
    public function deleteArticles(Request $request, $id = null, $userId = null)
    {
        if (!$id || !$userId) {
            return response()->json(['error' => 'Dates incompletes'], 400);
        }
        
        $article = Article::where('ID', $id)
                         ->where('usuari_id', $userId)
                         ->first();

        if (!$article) {
            return response()->json(['error' => 'No tens permisos per eliminar aquest article'], 403);
        }
        
        $result = $article->delete();
        
        return response()->json(['success' => $result]);
    }
}