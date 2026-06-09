<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chamado extends Model {
    protected $primaryKey = 'id_chamado';

    protected $fillable = [
        'descricao',
        'prioridade',
        'status',
        'data_abertura',
        'data_conclusao',
        'id_local',
        'id_tipo',
        'id_equipamento',
        'id_usuario',
        'id_usuario_responsavel',
        'secao_tecnica',
        'complexidade',
        'tipo_trabalho',
        'tipo_chamado',
        'id_patrimonio',
        'status_descricao',
        'nome_tecnico_responsavel',
        'data_ultimo_status',
    ];

    protected $casts = [
        'data_abertura' => 'datetime',
        'data_conclusao' => 'datetime',
        'data_ultimo_status' => 'datetime',
    ];

    public function usuario() {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function usuarioResponsavel() {
        return $this->belongsTo(User::class, 'id_usuario_responsavel');
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

    public function historicoStatus() {
        return $this->hasMany(HistoricoStatusChamado::class, 'id_chamado');
    }
}
