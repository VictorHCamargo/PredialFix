<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Local extends Model {
    protected $table = 'locais';
    protected $primaryKey = 'id_local';
    protected $fillable = ['sala_setor', 'andar', 'bloco'];

    // Um local pode ter vários chamados
    public function chamados() {
        return $this->hasMany(Chamado::class, 'id_local');
    }
}
