<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = ['nome', 'email', 'senha', 'cod_entrada'];

    protected $hidden = ['senha', 'remember_token'];

    public function getAuthPassword() {
        return $this->senha;
    }
}
