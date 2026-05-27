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
        'description',
        'begin_date',
        'end_date'
    ];


}
