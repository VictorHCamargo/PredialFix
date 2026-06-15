<?php

namespace Tests\Unit;

use App\Models\Chamado;
use App\Models\Feedback;
use App\Models\User;
use Tests\TestCase;

class UserTicketVisibilityTest extends TestCase
{
    public function test_professor_without_entry_code_can_view_all_tickets(): void
    {
        $user = new User([
            'nivel_acesso' => 'professor',
            'cod_entrada' => null,
        ]);

        $this->assertTrue($user->canViewAllTickets());
    }

    public function test_user_without_role_or_entry_code_cannot_view_all_tickets(): void
    {
        $user = new User([
            'nivel_acesso' => null,
            'cod_entrada' => null,
        ]);

        $this->assertFalse($user->canViewAllTickets());
    }

    public function test_user_can_rate_completed_ticket_without_feedback(): void
    {
        $user = new User([
            'nivel_acesso' => 'tecnico_manutencao',
            'cod_entrada' => null,
        ]);

        $chamado = new Chamado(['status' => 'concluido']);
        $chamado->setRelation('feedback', null);

        $this->assertTrue($user->canRateTicket($chamado));
    }

    public function test_user_cannot_rate_open_ticket(): void
    {
        $user = new User([
            'nivel_acesso' => 'professor',
            'cod_entrada' => null,
        ]);

        $chamado = new Chamado(['status' => 'aberto']);
        $chamado->setRelation('feedback', null);

        $this->assertFalse($user->canRateTicket($chamado));
    }

    public function test_user_cannot_rate_ticket_that_already_has_feedback(): void
    {
        $user = new User([
            'nivel_acesso' => 'administrador',
            'cod_entrada' => null,
        ]);

        $chamado = new Chamado(['status' => 'concluido']);
        $chamado->setRelation('feedback', new Feedback(['nota' => 5]));

        $this->assertFalse($user->canRateTicket($chamado));
    }
}
