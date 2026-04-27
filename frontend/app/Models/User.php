<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nome', 'name', 'email', 'cpf', 'id_profile', 'senha', 'password'])]
#[Hidden(['senha', 'remember_token'])]
class User extends Authenticatable
{
    protected $fillable = ['nome', 'name', 'email', 'cpf', 'id_profile', 'senha', 'password'];

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'senha' => 'hashed',
        ];
    }

    public function getAuthPasswordName()
    {
        return 'senha';
    }
    public function getAuthPassword()
    {
        return $this->senha;
    }
    public function getNameAttribute()
    {
        return $this->nome;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['nome'] = $value;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['senha'] = $value;
    }

    public function perfil()
    {
        return $this->belongsTo(Profile::class, 'id_profile');
    }
}