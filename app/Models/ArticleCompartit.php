<?php
/**
 * Practica7 Laravel Webs - Alberto González - 2nDAW
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleCompartit extends Model
{
    /*
     * Taula de la base de dades associada amb el model
     */
    protected $table = 'articles_compartits';
    
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
        'usuari_id', 'titol', 'cos', 'data_compartit', 'article_id', 'font_origen'
    ];

    /*
     * Relació amb el model Usuari
     */
    public function usuari()
    {
        return $this->belongsTo(Usuari::class, 'usuari_id');
    }
}