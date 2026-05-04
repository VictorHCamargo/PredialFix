<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('chamados', function (Blueprint $table) {
            $table->id('id_chamado');
            $table->text('descricao');
            $table->timestamp('data_abertura');
            $table->timestamp('data_conclusao')->nullable();
            $table->enum('prioridade', ['baixa', 'media', 'alta']);
            $table->enum('status', ['aberto', 'em_andamento', 'concluido', 'cancelado']);

            $table->foreignId('id_local')->constrained('locais', 'id_local');
            $table->foreignId('id_tipo')->constrained('tipo_problemas', 'id_tipo');
            $table
                ->foreignId('id_equipamento')
                ->nullable()
                ->constrained('equipamentos', 'id_equipamento');
            $table
                ->foreignId('id_orcamento')
                ->nullable()
                ->constrained('orcamentos', 'id_orcamento');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('chamados');
    }
};
