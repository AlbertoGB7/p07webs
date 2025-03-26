<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Usuari extends Authenticatable
{
    /*
     * Taula de la base de dades associada amb el model
     */
    protected $table = 'usuaris';
    
    /*
     * Clau primària de la taula
     */
    protected $primaryKey = 'id';
    
    /*
     * Desactivar timestamps automàtics
     */
    public $timestamps = false;

    /*
     * Camps que es poden assignar massivament
     */
    protected $fillable = [
        'usuari', 'contrasenya', 'correo', 'imatge', 'rol',
        'aut_social', 'token_recuperacion', 'token_expiracio_restablir',
        'token_remember', 'token_remember_expiracio'
    ];

    /*
     * Mètode per autenticació: retorna la contrasenya de l'usuari
     */
    public function getAuthPassword()
    {
        return $this->contrasenya;
    }

    /*
     * Obté un usuari pel seu nom d'usuari
     */
    public static function obtenirPerNom($nom)
    {
        return self::where('usuari', $nom)->first();
    }

    /*
     * Obté un usuari pel seu correu electrònic
     */
    public static function obtenirPerCorreu($correu)
    {
        return self::where('correo', $correu)->first();
    }

    /*
     * Obté un usuari pel seu token de "recordar-me"
     */
    public static function obtenirPerToken($token)
    {
        return self::where('token_remember', $token)
            ->where('token_remember_expiracio', '>', now())
            ->first();
    }

    /*
     * Obté un usuari pel seu token de recuperació
     */
    public static function obtenirPerTokenRecuperacio($token)
    {
        return self::where('token_recuperacion', $token)
            ->where('token_expiracio_restablir', '>', now())
            ->first();
    }

    /*
     * Obté tots els usuaris
     */
    public static function obtenirTots()
    {
        return self::select('id', 'usuari', 'correo', 'rol')->get();
    }

    /*
     * Crea un nou usuari a partir d'autenticació social
     */
    public static function insertarUsuariGoogle($correo)
    {
        return self::create([
            'correo' => $correo,
            'aut_social' => 'si',
            'rol' => 'user'
        ]);
    }

    /*
     * Guarda el token de recuperació de contrasenya
     */
    public function guardarTokenRecuperacio($token)
    {
        $this->token_recuperacion = $token;
        $this->token_expiracio_restablir = now()->addHour();
        $this->save();
    }

    /*
     * Elimina el token de recuperació
     */
    public function eliminarTokenRecuperacio()
    {
        $this->token_recuperacion = null;
        $this->token_expiracio_restablir = null;
        $this->save();
    }

    /*
     * Actualitza la contrasenya de l'usuari
     */
    public function actualitzarContrasenya($hash)
    {
        $this->contrasenya = $hash;
        $this->save();
    }

    /*
     * Actualitza el nom d'usuari
     */
    public function actualitzarNom($nouNom)
    {
        $this->usuari = $nouNom;
        $this->save();
    }

    /*
     * Actualitza la imatge de perfil
     */
    public function actualitzarImatge($ruta)
    {
        $this->imatge = $ruta;
        $this->save();
    }

    /*
     * Elimina el token "recordar-me"
     */
    public function eliminarTokenRemember()
    {
        $this->token_remember = null;
        $this->token_remember_expiracio = null;
        $this->save();
    }

    /*
     * Guarda el token "recordar-me"
     */
    public function guardarTokenRemember($token)
    {
        $this->token_remember = $token;
        $this->token_remember_expiracio = now()->addDays(7);
        $this->save();
    }
}