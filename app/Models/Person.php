<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Person extends Model
{

    protected $table = 'persons';
    protected $primaryKey = 'person_id';
    // 2. Dile a Laravel que no es un entero autoincrementable
    public $incrementing = false; //para genera un uuid atmatico UUID 1. punto
    protected $keyType = 'string'; //para genera un uuid atmatico UUID 2. punto
    protected $fillable = [
         'person_id',
        'first_name',
        'last_name',
        'identification_type',
        'identification_number',
        'image_url',
        'email',
        'phone_number',
        'birth_date'
    ];

    protected static function boot() //para genera un uuid atmatico UUID 3. punto
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->person_id) {
                $model->person_id = Str::uuid()->toString();
            }
        });
    }


    public function user()
    {
        return $this->hasOne(User::class);
    }
}
