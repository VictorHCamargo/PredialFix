<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'identificador'];

    /**
     * Relacionamento: Um perfil possui vários usuários
     */
    public function usuarios()
    {
        return $this->hasMany(User::class, 'id_profile');
    }
}
