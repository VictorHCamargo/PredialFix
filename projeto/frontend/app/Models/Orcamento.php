<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orcamento extends Model {
    protected $primaryKey = 'id_orcamento';
    protected $fillable = ['valor', 'aprovacao', 'data_verificacao', 'descricao'];
    public function chamado() {
        return $this->hasOne(Chamado::class, 'id_orcamento');
    }
}
