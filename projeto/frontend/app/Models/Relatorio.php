<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relatorio extends Model {
    protected $primaryKey = 'id_relatorio';
    protected $fillable = [
        'titulo',
        'tipo_relatorio',
        'data_relatorio',
        'prioridade',
        'status',
        'id_usuario',
    ];

    public function usuario() {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
