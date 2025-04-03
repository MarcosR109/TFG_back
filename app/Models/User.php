<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Kyojin\JWT\Traits\HasJWT;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasJWT;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'role_id',
    ];
    /* public function payload(): array
    {
        return [
            ...parent::payload(), // initializing the parent payload
            'role_id' => $this->role, // adding your custom values
        ];
    }*/
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

    public function canciones()
    {
        return $this->hasMany(Cancione::class);
    }
    public function favoritos()
    {
        return $this->belongsToMany(Cancione::class, 'favoritos');
    }
    public function guardados()
    {
        return $this->belongsToMany(Cancione::class, 'guardados');
    }
}
