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
        'ativo',
    ];

    protected $hidden = ['senha', 'remember_token'];

    protected $casts = [
        'ativo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
     * Verificar se o usuário é aluno
     */
    public function isAluno() {
        return $this->nivel_acesso === 'aluno';
    }

    /**
     * Verificar se o usuário é professor
     */
    public function isProfessor() {
        return $this->nivel_acesso === 'professor';
    }

    /**
     * Verificar se pode ver o dashboard
     */
    public function canSeeDashboard() {
        return in_array($this->nivel_acesso, [
            'administrador',
            'gerente_manutencao',
            'tecnico_manutencao',
            'aluno',
            'professor'
        ]);
    }

    /**
     * Verificar se pode gerenciar chamados (alterar status, prioridade, etc)
     */
    public function canManageTickets() {
        return in_array($this->nivel_acesso, [
            'administrador',
            'gerente_manutencao',
            'tecnico_manutencao'
        ]);
    }

    /**
     * Verificar se pode avaliar chamados
     */
    public function canRateTickets() {
        return in_array($this->nivel_acesso, [
            'aluno',
            'professor',
            'administrador'
        ]);
    }

    /**
     * Verificar se pode editar um chamado específico
     */
    public function canEditTicket(Chamado $chamado) {
        // Admin pode editar qualquer chamado
        if ($this->isAdmin()) {
            return true;
        }

        // Aluno pode editar apenas seus próprios chamados e apenas se estiver aberto
        if ($this->isAluno()) {
            return $chamado->id_usuario === $this->id_usuario && $chamado->status === 'aberto';
        }

        // Equipe de manutenção pode editar seus chamados atribuídos
        if ($this->isEquipeManutenacao()) {
            return $chamado->id_usuario === $this->id_usuario && $chamado->status === 'aberto';
        }

        return false;
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
     * Verificar se usuário tem código de entrada válido (pode acessar mais funcionalidades)
     */
    public function temCodigoEntrada() {
        return !is_null($this->cod_entrada) && !empty($this->cod_entrada);
    }
}
