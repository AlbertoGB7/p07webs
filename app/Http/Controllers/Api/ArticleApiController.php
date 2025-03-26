<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Validator;

class ArticleApiController extends Controller
{
    /**
     * Obtenir articles amb paginació
     * GET /api/articles
     * GET /api/articles/{user_id}
     * 
     * @param Request $request
     * @param int|null $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArticles(Request $request, $userId = null)
    {
        // Obtenir paràmetres de paginació
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 10);

        $query = Article::query();
        
        // Filtrar per usuari si s'especifica
        if ($userId) {
            $query->where('usuari_id', $userId);
        }
        
        $articles = $query->offset($offset)
                         ->limit($limit)
                         ->get();
        
        return response()->json($articles);
    }

    /**
     * Crear un nou article
     * POST /api/articles
     * 
     */
    public function postArticles(Request $request)
    {
        // Validar les dades d'entrada
        $validator = Validator::make($request->all(), [
            'titol' => 'required|string',
            'cos' => 'required|string',
            'usuari_id' => 'required|integer|exists:usuaris,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Dades incompletes'], 400);
        }

        // Crear el nou article
        $article = new Article();
        $article->titol = $request->input('titol');
        $article->cos = $request->input('cos');
        $article->usuari_id = $request->input('usuari_id');
        $article->data = now();
        
        $result = $article->save();
        
        return response()->json(['success' => $result]);
    }

    /**
     * Actualitzar un article existent
     * PUT /api/articles
     */
    public function putArticles(Request $request)
    {
        // Validar les dades d'entrada
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:articles,ID',
            'usuari_id' => 'required|integer|exists:usuaris,id',
            'nou_valor' => 'required|string',
            'camp' => 'required|string|in:titol,cos'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Dades incompletes'], 400);
        }

        // Verificar que l'usuari és propietari de l'article
        $article = Article::where('ID', $request->input('id'))
                         ->where('usuari_id', $request->input('usuari_id'))
                         ->first();

        if (!$article) {
            return response()->json(['error' => 'No tens permisos per modificar aquest article'], 403);
        }
        
        // Actualitzar el camp especificat
        $campo = $request->input('camp');
        $article->$campo = $request->input('nou_valor');
        $result = $article->save();
        
        return response()->json(['success' => $result]);
    }

    /**
     * Eliminar un article
     * DELETE /api/articles/{id}/{user_id}
     * 
     */
    public function deleteArticles(Request $request, $id = null, $userId = null)
    {
        // Validar que s'han proporcionat els paràmetres necessaris
        if (!$id || !$userId) {
            return response()->json(['error' => 'Dades incompletes'], 400);
        }
        
        // Verificar que l'usuari és propietari de l'article
        $article = Article::where('ID', $id)
                         ->where('usuari_id', $userId)
                         ->first();

        if (!$article) {
            return response()->json(['error' => 'No tens permisos per eliminar aquest article'], 403);
        }
        
        // Eliminar l'article
        $result = $article->delete();
        
        return response()->json(['success' => $result]);
    }
}