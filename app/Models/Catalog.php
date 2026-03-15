<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Catalog extends Model
{
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    protected $table = 'catalogs';
    protected $primaryKey = 'catalog_id';

    protected $fillable = [
        'name',
        'key',
    ];

    public function catalog_details(): HasMany
    {
        return $this->hasMany(CatalogDetail::class, 'catalog_id');
    }
}
