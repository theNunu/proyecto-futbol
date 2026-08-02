<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CatalogDetail extends Model
{
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    protected $table = 'catalog_details';
    protected $primaryKey = 'catalog_detail_id';

    protected $fillable = [
        'catalog_id',
        'name',
        'key',
    ];


    public function catalog(): BelongsTo
    {
        return $this->belongsTo(Catalog::class, 'catalog_detail_id');
    }

    public function news(): BelongsToMany
    {
        return $this->belongsToMany(
            News::class,   // Modelo destino (inverso)
            'category_news',     // Misma tabla intermedia
            'catalog_detail_id',     // Llave foránea de este modelo (Role) en la pivot
            'news_id'      // Llave foránea del modelo destino (User) en la pivot
        );
    }
}
