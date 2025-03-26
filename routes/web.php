<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Rutes Principals
|--------------------------------------------------------------------------
*/

// Ruta d'inici
Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

// Ruta pública per mostrar articles (accessible sense login)
Route::get('/mostrar', [App\Http\Controllers\MostrarController::class, 'index'])->name('mostrar');

/*
|--------------------------------------------------------------------------
| Autenticació i Gestió d'Usuaris
|--------------------------------------------------------------------------
*/

// Login i Registre tradicional
Route::get('/login', [App\Http\Controllers\LoginController::class, 'mostrarLogin']);
Route::post('/login', [App\Http\Controllers\LoginController::class, 'verificarLogin'])->name('login');
Route::get('/registre', [App\Http\Controllers\LoginController::class, 'mostrarRegistre']);
Route::post('/registre', [App\Http\Controllers\LoginController::class, 'verificarLogin'])->name('registre');
Route::post('/logout', function () {
    Session::flash('missatge_logout', "T'has desloguejat amb èxit");
    Session::flush();
    return redirect('/');
})->name('logout');

// Autenticació amb Google
Route::get('/auth/google', [App\Http\Controllers\SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\SocialAuthController::class, 'handleGoogleCallback']);

// Autenticació amb GitHub
Route::get('/auth/github', [App\Http\Controllers\GithubAuthController::class, 'redirectToGithub'])->name('auth.github');
Route::get('/auth/github/callback', [App\Http\Controllers\GithubAuthController::class, 'handleGithubCallback']);

// Recuperació de contrasenya
Route::get('/enviar_correu', [App\Http\Controllers\RecuperacioController::class, 'mostrarFormulariCorreu']);
Route::post('/enviar_correu', [App\Http\Controllers\RecuperacioController::class, 'enviarCorreu']);
Route::get('/restablir_contrasenya/{token}', [App\Http\Controllers\RecuperacioController::class, 'mostrarFormulariRestablir']);
Route::post('/restablir_contrasenya', [App\Http\Controllers\RecuperacioController::class, 'restablirContrasenya']);

// Gestió d'usuaris (només admin)
Route::get('/gestionar_usuaris', [App\Http\Controllers\GestionarUsuarisController::class, 'mostrarUsuaris'])->name('gestionar_usuaris');
Route::post('/gestionar_usuaris', [App\Http\Controllers\GestionarUsuarisController::class, 'mostrarUsuaris']);
Route::post('/eliminar_usuari', [App\Http\Controllers\GestionarUsuarisController::class, 'eliminarUsuari'])->name('eliminar_usuari');

/*
|--------------------------------------------------------------------------
| Perfil d'Usuari
|--------------------------------------------------------------------------
*/

// Índex d'usuari
Route::get('/index_usuari', [App\Http\Controllers\LoginController::class, 'mostrarIndexUsuari']);

// Modificar perfil
Route::get('/modificar_perfil', [App\Http\Controllers\PerfilController::class, 'mostrarFormulari']);
Route::post('/modificar_perfil', [App\Http\Controllers\PerfilController::class, 'actualitzarPerfil']);

// Modificar contrasenya
Route::get('/modificar_contrasenya', [App\Http\Controllers\ContrasenyaController::class, 'mostrarFormulari']);
Route::post('/modificar_contrasenya', [App\Http\Controllers\ContrasenyaController::class, 'actualitzarContrasenya']);

/*
|--------------------------------------------------------------------------
| Gestió d'Articles
|--------------------------------------------------------------------------
*/

// Insertar articles
Route::get('/insertar', [App\Http\Controllers\InsertarController::class, 'mostrarFormulari']);
Route::post('/insertar', [App\Http\Controllers\InsertarController::class, 'insertar']);
Route::post('/insertar_controlador', [App\Http\Controllers\InsertarController::class, 'insertar']);

// Mostrar articles de l'usuari
Route::get('/mostrar_usuari', [App\Http\Controllers\MostrarUsuariController::class, 'index'])->name('mostrar_usuari');

// Modificar articles
Route::get('/modificar', [App\Http\Controllers\ModificarController::class, 'mostrarFormulari']);
Route::post('/modificar_controlador', [App\Http\Controllers\ModificarController::class, 'processarFormulari']);

// Eliminar articles
Route::get('/eliminar', [App\Http\Controllers\EliminarController::class, 'mostrarFormulari']);
Route::post('/eliminar', [App\Http\Controllers\EliminarController::class, 'buscarOEliminar']);

/*
|--------------------------------------------------------------------------
| Articles Compartits, QR i API
|--------------------------------------------------------------------------
*/

// Articles compartits (Ajax)
Route::get('/vistaAjax', [App\Http\Controllers\AjaxController::class, 'mostrarArticlesCompartits']);
Route::get('/copiarAjax', [App\Http\Controllers\AjaxController::class, 'mostrarCopiarArticle']);
Route::get('/controladorAjax', [App\Http\Controllers\AjaxController::class, 'controladorAjax']);
Route::post('/controladorAjax', [App\Http\Controllers\AjaxController::class, 'controladorAjax']);

// Generació i lectura de codis QR
Route::get('/lectura_qr', [App\Http\Controllers\QrController::class, 'mostrarFormulari'])->name('lectura_qr');
Route::post('/processar_qr', [App\Http\Controllers\QrController::class, 'processarQr'])->name('processar_qr');
Route::get('/generar_qr', [App\Http\Controllers\QrGeneratorController::class, 'generarQr'])->name('generar_qr');

// API
Route::get('/lectura_api', [App\Http\Controllers\ApiLecturaController::class, 'mostrarFormulari'])->name('lectura_api');