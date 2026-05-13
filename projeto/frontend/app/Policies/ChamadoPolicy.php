<?php

namespace App\Policies;

use App\Models\Chamado;
use App\Models\User;

class ChamadoPolicy
{
    /**
     * Determinar se o usuário pode ver todos os chamados
     */
    public function viewAny(User $user): bool
    {
        // Todos os usuários autenticados podem listar chamados (com filtros aplicados)
        return true;
    }

    /**
     * Determinar se o usuário pode ver um chamado específico
     */
    public function view(User $user, Chamado $chamado): bool
    {
        // Admin vê todos
        if ($user->isAdmin()) {
            return true;
        }

        // Equipe de manutenção pode ver todos
        if ($user->isEquipeManutenacao()) {
            return true;
        }

        // Alunos veem apenas seus próprios chamados
        if ($user->isAluno()) {
            return $chamado->id_usuario === $user->id_usuario;
        }

        return false;
    }

    /**
     * Determinar se o usuário pode criar um chamado
     */
    public function create(User $user): bool
    {
        // Apenas professores, técnicos, gerentes e admin podem criar chamados
        return in_array($user->nivel_acesso, [
            'professor',
            'tecnico_manutencao',
            'gerente_manutencao',
            'administrador'
        ]);
    }

    /**
     * Determinar se o usuário pode editar um chamado
     */
    public function update(User $user, Chamado $chamado): bool
    {
        return $user->canEditTicket($chamado);
    }

    /**
     * Determinar se o usuário pode deletar um chamado
     */
    public function delete(User $user, Chamado $chamado): bool
    {
        // Alunos nunca podem deletar
        if ($user->isAluno()) {
            return false;
        }
        
        // Admin pode deletar qualquer um
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determinar se o usuário pode alterar o status de um chamado
     */
    public function updateStatus(User $user, Chamado $chamado): bool
    {
        // Alunos nunca podem alterar status
        if ($user->isAluno()) {
            return false;
        }

        // Admin sempre pode
        if ($user->isAdmin()) {
            return true;
        }

        // Equipe de manutenção pode
        if ($user->isEquipeManutenacao()) {
            return true;
        }

        return false;
    }

    /**
     * Determinar se o usuário pode avaliar um chamado
     */
    public function rate(User $user, Chamado $chamado): bool
    {
        // Alunos nunca podem avaliar
        if ($user->isAluno()) {
            return false;
        }

        // Professores podem avaliar qualquer chamado
        if ($user->isProfessor()) {
            return true;
        }

        // Admin pode avaliar qualquer um
        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }
}
