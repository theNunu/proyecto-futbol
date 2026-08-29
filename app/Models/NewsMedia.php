<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsMedia extends Model
{
    //EL ID ES UN UUID no entero incrementable (3 puntos)
    protected $table = 'news_media';

    protected $primaryKey = 'news_media_id';


    // 2. Dile a Laravel que no es un entero autoincrementable
    public $incrementing = false; //para genera un uuid atmatico UUID 1. punto

    // 3. Especifica que el tipo de la clave es un string
    protected $keyType = 'string'; //para genera un uuid atmatico UUID 2. punto

    protected $fillable = [
        'new_id',
        'file_id',
        'type',
        'url_externo'
    ];

    protected static function boot() //para genera un uuid atmatico UUID 3. punto
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->news_media_id) {
                $model->news_media_id = Str::uuid()->toString();
            }
        });
    }
}
