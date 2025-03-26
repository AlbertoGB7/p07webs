<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ArticleApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

// Rutes per articles
Route::get('/articles', [ArticleApiController::class, 'getArticles']);
Route::get('/articles/{userId}', [ArticleApiController::class, 'getArticles']);
Route::post('/articles', [ArticleApiController::class, 'postArticles']);
Route::put('/articles', [ArticleApiController::class, 'putArticles']);
Route::delete('/articles/{id}/{userId}', [ArticleApiController::class, 'deleteArticles']);