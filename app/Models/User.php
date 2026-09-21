<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'person_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 🔐 JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    // Un usuario pertenece a una única persona
    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    // public function roles(): BelongsToMany
    // {
    //     return $this->belongsToMany(
    //         Role::class, // 1. Modelo con el que se relaciona
    //         'role_user', // 2. Nombre de la tabla intermedia
    //         'user_id',   // 3. Llave foránea de User en la tabla intermedia
    //         'role_id'    // 4. Llave foránea de Role en la tabla intermedia
    //     );
    // }
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_user',
            'user_id',   // Llave foránea de User en la tabla pivot
            'role_id',   // Llave foránea de Role en la tabla pivot
            'id',        // Llave primaria local en la tabla de usuarios ("id")
            'role_id'    // Llave primaria local en la tabla de roles ("role_id")
        )
            ->using(RoleUser::class)
            ->withPivot('role_user_id'); // 👈 Obliga a Laravel a cargar la columna UUID de la pivot
    }

}
