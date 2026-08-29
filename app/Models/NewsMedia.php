<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsMedia extends Model
{
    //
    protected $table = 'news_media';

    protected $primaryKey = 'news_media_id';

    protected $fillable = [
        'new_id',
        'file_id',
        'type',
        'url_externo'
    ];
}
