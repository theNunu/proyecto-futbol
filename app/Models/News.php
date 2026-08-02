<?php

namespace App\Models;

use App\Models\CatalogDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    public function files()
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    // Una noticia puede tener una imagen (o muchas, usando morphMany)
    public function image()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function catalog_details(): BelongsToMany
    {
        return $this->belongsToMany(
            CatalogDetail::class,   // Modelo destino
            'category_news',       // Tabla intermedia
            'news_id',     // Llave foránea de este modelo (User) en la pivot
            'catalog_detail_id'      // Llave foránea del modelo destino (Role) en la pivot
        );
    }

}
