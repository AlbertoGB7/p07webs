<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class Usuari extends Authenticatable
{
    protected $table = 'usuaris';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'usuari', 'contrasenya', 'correo', 'imatge', 'rol',
        'aut_social', 'token_recuperacion', 'token_expiracio_restablir',
        'token_remember', 'token_remember_expiracio'
    ];

    public function getAuthPassword()
    {
        return $this->contrasenya;
    }

    // Métodos personalizados (equivalentes a los que tenías en UsuariModel.php)

    public static function obtenirPerNom($nom)
    {
        return self::where('usuari', $nom)->first();
    }

    public static function obtenirPerCorreu($correu)
    {
        return self::where('correo', $correu)->first();
    }

    public static function obtenirPerToken($token)
    {
        return self::where('token_remember', $token)
            ->where('token_remember_expiracio', '>', now())
            ->first();
    }

    public static function obtenirPerTokenRecuperacio($token)
    {
        return self::where('token_recuperacion', $token)
            ->where('token_expiracio_restablir', '>', now())
            ->first();
    }

    public static function obtenirTots()
    {
        return self::select('id', 'usuari', 'correo', 'rol')->get();
    }

    public static function insertarUsuariGoogle($correo)
    {
        return self::create([
            'correo' => $correo,
            'aut_social' => 'si',
            'rol' => 'user'
        ]);
    }

    public function guardarTokenRecuperacio($token)
    {
        $this->token_recuperacion = $token;
        $this->token_expiracio_restablir = now()->addHour();
        $this->save();
    }

    public function eliminarTokenRecuperacio()
    {
        $this->token_recuperacion = null;
        $this->token_expiracio_restablir = null;
        $this->save();
    }

    public function actualitzarContrasenya($hash)
    {
        $this->contrasenya = $hash;
        $this->save();
    }

    public function actualitzarNom($nouNom)
    {
        $this->usuari = $nouNom;
        $this->save();
    }

    public function actualitzarImatge($ruta)
    {
        $this->imatge = $ruta;
        $this->save();
    }

    public function eliminarTokenRemember()
    {
        $this->token_remember = null;
        $this->token_remember_expiracio = null;
        $this->save();
    }

    public function guardarTokenRemember($token)
    {
        $this->token_remember = $token;
        $this->token_remember_expiracio = now()->addDays(7);
        $this->save();
    }
}
