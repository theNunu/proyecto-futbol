<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}
