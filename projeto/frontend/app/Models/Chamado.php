<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chamado extends Model {
    protected $primaryKey = 'id_chamado';
    protected $fillable = [
        'descricao',
        'prioridade',
        'status',
        'id_local',
        'id_tipo',
        'id_equipamento',
        'id_usuario',
    ];

    public function usuario() {
        return $this->belongsTo(User::class, 'id_usuario');
    }
    public function local() {
        return $this->belongsTo(Local::class, 'id_local');
    }
    public function tipoProblema() {
        return $this->belongsTo(TipoProblema::class, 'id_tipo');
    }
    public function equipamento() {
        return $this->belongsTo(Equipamento::class, 'id_equipamento');
    }
    public function feedback() {
        return $this->hasOne(Feedback::class, 'id_chamado');
    }
}
