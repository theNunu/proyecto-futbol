<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $primaryKey = 'file_id';
    protected $fillable = ['name', 'path', 'mime_type', 'size', 'fileable_id', 'fileable_type'];

    // Relación polimórfica inversa
    public function fileable()
    {
        return $this->morphTo();
    }
}
