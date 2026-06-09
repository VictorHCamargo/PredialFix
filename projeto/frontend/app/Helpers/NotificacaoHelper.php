<?php

namespace App\Helpers;

use App\Models\Chamado;
use App\Models\Notificacao;
use App\Models\User;

class NotificacaoHelper {
    public static function disparar(string $mensagem, string $tipo, ?int $idChamado, array $destinatarios): void {
        foreach (array_unique($destinatarios) as $idUsuario) {
            Notificacao::create([
                'id_usuario' => $idUsuario,
                'mensagem' => $mensagem,
                'tipo' => $tipo,
                'id_chamado' => $idChamado,
            ]);
        }
    }

    public static function administradoresAtivos(): array {
        return User::where('nivel_acesso', 'administrador')
            ->where('ativo', true)
            ->pluck('id_usuario')
            ->all();
    }

    public static function equipeManutencaoAtiva(): array {
        return User::where('nivel_acesso', 'tecnico_manutencao')
            ->where('ativo', true)
            ->pluck('id_usuario')
            ->all();
    }

    public static function obterDestinatarios(string $tipo, Chamado $chamado, ?User $ator = null): array {
        $admins = self::administradoresAtivos();
        $equipe = self::equipeManutencaoAtiva();
        $solicitante = [$chamado->id_usuario];
        $responsavel = $chamado->id_usuario_responsavel ? [$chamado->id_usuario_responsavel] : [];
        $atorId = $ator?->id_usuario ? [$ator->id_usuario] : [];

        return match ($tipo) {
            'criacao' => array_merge($admins, $equipe),
            'edicao' => array_merge($admins, $equipe, $solicitante),
            'cancelamento' => array_merge($admins, $equipe, $solicitante, $responsavel),
            'status' => array_merge($admins, $equipe, $solicitante, $responsavel, $atorId),
            'prioridade',
            'complexidade' => array_merge($admins, $equipe, $solicitante),
            'atribuicao' => array_merge($admins, $equipe, $solicitante, $responsavel),
            default => array_merge($admins, $equipe, $atorId),
        };
    }
}
