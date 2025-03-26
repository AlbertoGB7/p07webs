<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleCompartit extends Model
{
    protected $table = 'articles_compartits';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'usuari_id', 'titol', 'cos', 'data_compartit', 'article_id', 'font_origen'
    ];

    public function usuari()
    {
        return $this->belongsTo(Usuari::class, 'usuari_id');
    }
}
