<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nome',
        'email',
        'senha',
        'cod_entrada',
        'nivel_acesso',
        'setor',
        'ativo'
    ];

    protected $hidden = ['senha', 'remember_token'];

    protected $casts = [
        'ativo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Obter a senha criptografada para autenticação
     */
    public function getAuthPassword() {
        return $this->senha;
    }

    /**
     * Verificar se o usuário é administrador
     */
    public function isAdmin() {
        return $this->nivel_acesso === 'administrador';
    }

    /**
     * Verificar se o usuário é gerente de manutenção
     */
    public function isGerenteManutenacao() {
        return $this->nivel_acesso === 'gerente_manutencao';
    }

    /**
     * Verificar se o usuário é técnico de manutenção
     */
    public function isTecnicoManutenacao() {
        return $this->nivel_acesso === 'tecnico_manutencao';
    }

    /**
     * Verificar se é membro da equipe de manutenção (técnico ou gerente)
     */
    public function isEquipeManutenacao() {
        return in_array($this->nivel_acesso, ['gerente_manutencao', 'tecnico_manutencao']);
    }

    /**
     * Verificar se é usuário comum (sem privilégios)
     */
    public function isVisitante() {
        return $this->nivel_acesso === 'visitante';
    }

    /**
     * Relacionamento com chamados criados
     */
    public function chamadosCriados() {
        return $this->hasMany(Chamado::class, 'id_usuario');
    }

    /**
     * Relacionamento com chamados onde é responsável
     */
    public function chamadosResponsaveis() {
        return $this->hasMany(Chamado::class, 'id_usuario_responsavel');
    }

    /**
     * Relacionamento com histórico de status
     */
    public function historicoStatus() {
        return $this->hasMany(HistoricoStatusChamado::class, 'id_usuario');
    }

    /**
     * Relacionamento com feedback
     */
    public function feedbacks() {
        return $this->hasMany(Feedback::class, 'id_usuario');
    }

    /**
     * Verificar se usuário tem código de entrada válido (pode acessar mais funcionalidades)
     */
    public function temCodigoEntrada() {
        return !is_null($this->cod_entrada) && !empty($this->cod_entrada);
    }
}

