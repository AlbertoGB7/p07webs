<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

Route::get('/login', function () {
    return view('login_nou');
});


use App\Http\Controllers\LoginController;

Route::get('/login', [LoginController::class, 'mostrarLogin']);
Route::post('/login', [LoginController::class, 'verificarLogin'])->name('login');
Route::get('/index_usuari', [LoginController::class, 'mostrarIndexUsuari']);
Route::get('/registre', [LoginController::class, 'mostrarRegistre']);
Route::post('/registre', [LoginController::class, 'verificarLogin'])->name('registre');

// INDEX USUARI
use App\Http\Controllers\InsertarController;

Route::get('/insertar', function () {
    return view('insertar');
});

Route::get('/insertar', [InsertarController::class, 'mostrarFormulari']);
Route::post('/insertar', [InsertarController::class, 'insertar']);

Route::post('/insertar_controlador', [InsertarController::class, 'insertar']);


use App\Http\Controllers\MostrarUsuariController;

Route::get('/mostrar_usuari', [MostrarUsuariController::class, 'index'])->name('mostrar_usuari');


use Illuminate\Support\Facades\Session;

use App\Http\Controllers\EliminarController;

Route::get('/eliminar', [EliminarController::class, 'mostrarFormulari']);
Route::post('/eliminar', [EliminarController::class, 'buscarOEliminar']);

use App\Http\Controllers\ModificarController;

// Rutas para modificar artículos
Route::get('/modificar', [ModificarController::class, 'mostrarFormulari']);
Route::post('/modificar_controlador', [ModificarController::class, 'processarFormulari']);


use App\Http\Controllers\PerfilController;

// Rutas para modificar perfil
Route::get('/modificar_perfil', [PerfilController::class, 'mostrarFormulari']);
Route::post('/modificar_perfil', [PerfilController::class, 'actualitzarPerfil']);


use App\Http\Controllers\ContrasenyaController;

// Rutas para modificar contrasenya
Route::get('/modificar_contrasenya', [ContrasenyaController::class, 'mostrarFormulari']);
Route::post('/modificar_contrasenya', [ContrasenyaController::class, 'actualitzarContrasenya']);

use App\Http\Controllers\AjaxController;

// Rutas para artículos compartidos (Ajax)
Route::get('/vistaAjax', [AjaxController::class, 'mostrarArticlesCompartits']);
Route::get('/copiarAjax', [AjaxController::class, 'mostrarCopiarArticle']);
Route::get('/controladorAjax', [AjaxController::class, 'controladorAjax']);
Route::post('/controladorAjax', [AjaxController::class, 'controladorAjax']);

use App\Http\Controllers\QrController;

// Rutas para lectura de códigos QR
Route::get('/lectura_qr', [QrController::class, 'mostrarFormulari'])->name('lectura_qr');
Route::post('/processar_qr', [QrController::class, 'processarQr'])->name('processar_qr');

use App\Http\Controllers\RecuperacioController;

// Rutas para recuperación de contraseña
Route::get('/enviar_correu', [RecuperacioController::class, 'mostrarFormulariCorreu']);
Route::post('/enviar_correu', [RecuperacioController::class, 'enviarCorreu']);
Route::get('/restablir_contrasenya/{token}', [RecuperacioController::class, 'mostrarFormulariRestablir']);
Route::post('/restablir_contrasenya', [RecuperacioController::class, 'restablirContrasenya']);

use App\Http\Controllers\GestionarUsuarisController;


// Rutas para gestionar usuarios (solo admin)
Route::get('/gestionar_usuaris', [GestionarUsuarisController::class, 'mostrarUsuaris'])->name('gestionar_usuaris');
Route::post('/gestionar_usuaris', [GestionarUsuarisController::class, 'mostrarUsuaris']);
Route::post('/eliminar_usuari', [GestionarUsuarisController::class, 'eliminarUsuari'])->name('eliminar_usuari');


use App\Http\Controllers\MostrarController;

// Ruta pública para mostrar artículos (accesible sin login)
Route::get('/mostrar', [MostrarController::class, 'index'])->name('mostrar');

use App\Http\Controllers\QrGeneratorController;

// Ruta para generar y descargar QR de un artículo
Route::get('/generar_qr', [QrGeneratorController::class, 'generarQr'])->name('generar_qr');


// En routes/web.php
use App\Http\Controllers\SocialAuthController;

// Rutas para autenticación con Google
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);


use App\Http\Controllers\GithubAuthController;

// Rutas para autenticación con GitHub
Route::get('/auth/github', [GithubAuthController::class, 'redirectToGithub'])->name('auth.github');
Route::get('/auth/github/callback', [GithubAuthController::class, 'handleGithubCallback']);

use App\Http\Controllers\ApiLecturaController;

// Ruta para la vista de lectura de API
Route::get('/lectura_api', [ApiLecturaController::class, 'mostrarFormulari'])->name('lectura_api');

Route::post('/logout', function () {
    // Guardar un mensaje flash en la sesión antes de destruirla
    Session::flash('missatge_logout', "T'has desloguejat amb èxit");
    
    // Eliminar todos los datos de la sesión
    Session::flush();
    
    return redirect('/');
})->name('logout');

