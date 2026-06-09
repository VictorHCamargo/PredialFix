<?php

namespace App\Policies;

use App\Models\Chamado;
use App\Models\User;

class ChamadoPolicy
{
    /**
     * Determinar se o usuario pode ver todos os chamados.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determinar se o usuario pode ver um chamado especifico.
     */
    public function view(User $user, Chamado $chamado): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isEquipeManutencao()) {
            return true;
        }

        if ($user->isProfessor()) {
            return true;
        }

        return false;
    }

    /**
     * Determinar se o usuario pode criar um chamado.
     */
    public function create(User $user): bool
    {
        return in_array($user->nivel_acesso, [
            'professor',
            'tecnico_manutencao',
            'administrador',
        ]);
    }

    /**
     * Determinar se o usuario pode editar um chamado.
     */
    public function update(User $user, Chamado $chamado): bool
    {
        if ($user->isAdmin() || $user->isEquipeManutencao()) {
            return true;
        }

        if ($user->isProfessor()) {
            return $chamado->id_usuario === $user->id_usuario
                && in_array($chamado->status, ['aberto', 'em_andamento']);
        }

        return false;
    }

    /**
     * Determinar se o usuario pode deletar um chamado.
     */
    public function delete(User $user, Chamado $chamado): bool
    {
        return $user->isAdmin() || $user->isEquipeManutencao();
    }

    /**
     * Determinar se o usuario pode alterar o status de um chamado.
     */
    public function updateStatus(User $user, Chamado $chamado): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isEquipeManutencao()) {
            return true;
        }

        return false;
    }

    /**
     * Determinar se o usuario pode avaliar um chamado.
     */
    public function rate(User $user, Chamado $chamado): bool
    {
        if ($user->isProfessor()) {
            return true;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return false;
    }
}
