<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    //
    protected $table = 'banners';
    protected $primaryKey = 'banner_id';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        // 'title',
        // 'summary',
        'file_id',
        // 'description',
        // 'begin_date',
        // 'end_date',
        // 'is_active'
    ];

    public function files() //para juntar banner con file
    {
        return $this->belongsTo(File::class, 'file_id');
    }

    // Una noticia puede tener una imagen (o muchas, usando morphMany)
    public function image() //para juntar banner con file
    {
        return $this->morphOne(File::class, 'fileable');
    }



}
