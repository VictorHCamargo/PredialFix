<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model {
    protected $table = 'notificacoes';

    protected $fillable = [
        'id_usuario',
        'mensagem',
        'tipo',
        'lida',
        'id_chamado',
    ];

    protected $casts = [
        'lida' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function usuario() {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function chamado() {
        return $this->belongsTo(Chamado::class, 'id_chamado', 'id_chamado');
    }
}
