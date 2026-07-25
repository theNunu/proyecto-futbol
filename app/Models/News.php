<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    protected $table = 'news';
    protected $primaryKey = 'news_id';

    protected $fillable = [
        'title',
        'summary',
        'file_id',
        'description',
        'begin_date',
        'end_date',
        'is_active'
    ];

    // Una noticia puede tener una imagen (o muchas, usando morphMany)
    public function image()
    {
        return $this->morphOne(File::class, 'fileable');
    }

}
