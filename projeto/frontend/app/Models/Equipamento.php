<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model {
    protected $primaryKey = 'id_equipamento';
    protected $fillable = ['tag_identificacao', 'nome_equipamento', 'marca', 'status'];

    // Um equipamento pode estar associado a vários chamados (histórico)
    public function chamados() {
        return $this->hasMany(Chamado::class, 'id_equipamento');
    }
}
