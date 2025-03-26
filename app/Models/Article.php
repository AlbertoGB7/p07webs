<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'articles';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = ['titol', 'cos', 'usuari_id', 'imagen_url', 'data'];

    public function usuari()
    {
        return $this->belongsTo(Usuari::class, 'usuari_id');
    }
}
