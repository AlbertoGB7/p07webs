<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /*
     * Taula de la base de dades associada amb el model
     */
    protected $table = 'articles';
    
    /*
     * Clau primària de la taula
     */
    protected $primaryKey = 'ID';
    
    /*
     * Desactivar timestamps automàtics
     */
    public $timestamps = false;

    /*
     * Camps que es poden assignar massivament
     */
    protected $fillable = ['titol', 'cos', 'usuari_id', 'imagen_url', 'data'];

    /*
     * Relació amb el model Usuari
     */
    public function usuari()
    {
        return $this->belongsTo(Usuari::class, 'usuari_id');
    }
}