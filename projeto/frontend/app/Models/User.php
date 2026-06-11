<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'cod_entrada',
        'nivel_acesso',
        'setor',
        'ativo',
    ];

    protected $hidden = ['senha', 'remember_token'];

    protected $casts = [
        'ativo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getAuthPassword() {
        return $this->senha;
    }

    public function isAdmin() {
        return $this->nivel_acesso === 'administrador';
    }

    public function isTecnicoManutencao() {
        return $this->nivel_acesso === 'tecnico_manutencao';
    }

    public function isTecnico() {
        return $this->isTecnicoManutencao();
    }

    public function isEquipeManutencao() {
        return $this->isTecnicoManutencao();
    }

    public function isProfessor() {
        return $this->nivel_acesso === 'professor';
    }

    public function canSeeDashboard() {
        return in_array($this->nivel_acesso, [
            'administrador',
            'tecnico_manutencao',
            'professor',
        ]);
    }

    public function canManageTickets() {
        return in_array($this->nivel_acesso, [
            'administrador',
            'tecnico_manutencao',
        ]);
    }

    public function canRateTickets() {
        return in_array($this->nivel_acesso, [
            'professor',
            'administrador',
        ]);
    }

    public function canEditTicket(Chamado $chamado) {
        // Admins podem editar qualquer chamado
        if ($this->isAdmin()) {
            return true;
        }

        // Técnicos podem editar APENAS chamados que criaram
        if ($this->isTecnico()) {
            return $chamado->id_usuario === $this->id_usuario;
        }

        // Professores podem editar apenas seus próprios chamados em status aberto ou em andamento
        if ($this->isProfessor()) {
            return $chamado->id_usuario === $this->id_usuario
                && in_array($chamado->status, ['aberto', 'em_andamento']);
        }

        return false;
    }

    public function chamadosCriados() {
        return $this->hasMany(Chamado::class, 'id_usuario');
    }

    public function chamadosResponsaveis() {
        return $this->hasMany(Chamado::class, 'id_usuario_responsavel');
    }

    public function historicoStatus() {
        return $this->hasMany(HistoricoStatusChamado::class, 'id_usuario');
    }

    public function notificacoes() {
        return $this->hasMany(Notificacao::class, 'id_usuario');
    }

    public function temCodigoEntrada() {
        return !is_null($this->cod_entrada) && !empty($this->cod_entrada);
    }
}
