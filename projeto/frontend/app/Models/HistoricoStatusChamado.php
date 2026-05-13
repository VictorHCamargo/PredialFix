<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoricoStatusChamado extends Model {
    protected $table = 'historico_status_chamados';
    protected $primaryKey = 'id_historico';

    protected $fillable = [
        'id_chamado',
        'status_anterior',
        'status_novo',
        'descricao_mudanca',
        'id_usuario',
        'prioridade',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function chamado() {
        return $this->belongsTo(Chamado::class, 'id_chamado');
    }

    public function usuario() {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}
