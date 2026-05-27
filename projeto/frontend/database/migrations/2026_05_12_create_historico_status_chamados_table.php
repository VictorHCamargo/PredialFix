<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('historico_status_chamados', function (Blueprint $table) {
            $table->id('id_historico');
            $table->foreignId('id_chamado')
                ->constrained('chamados', 'id_chamado')
                ->onDelete('cascade');
            $table->enum('status_anterior', ['aberto', 'em_andamento', 'concluido', 'cancelado']);
            $table->enum('status_novo', ['aberto', 'em_andamento', 'concluido', 'cancelado']);
            $table->text('descricao_mudanca')->nullable();
            $table->foreignId('id_usuario')
                ->constrained('usuarios', 'id_usuario')
                ->comment('Usuário que realizou a mudança de status');
            
            $table->enum('prioridade', ['baixa', 'media', 'alta'])->nullable()->comment('Prioridade definida nesta transição');
            
            $table->timestamps();
            
            // Índices
            $table->index('id_chamado');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_status_chamados');
    }
};
