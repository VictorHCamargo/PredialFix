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
        Schema::create('estoque_interno', function (Blueprint $table) {
            $table->id('id_estoque');
            $table->string('nome_item')->index();
            $table->text('descricao')->nullable();
            $table->integer('quantidade')->default(0);
            $table->string('categoria')->index();
            $table->string('localizacao')->nullable()->comment('Localização do item no estoque');
            $table->decimal('valor_unitario', 10, 2)->nullable();
            $table->decimal('valor_total', 10, 2)->nullable();
            $table->string('codigo_patrimonio')->unique()->nullable()->comment('Código de patrimônio único');
            $table->enum('status_item', ['disponivel', 'indisponivel', 'danificado', 'descartado'])->default('disponivel');
            $table->timestamp('data_entrada')->nullable();
            $table->timestamp('data_saida')->nullable();
            $table->text('observacoes')->nullable();
            
            // Relacionamento com usuario que cadastrou
            $table->foreignId('id_usuario_cadastro')
                ->constrained('usuarios', 'id_usuario')
                ->comment('Usuário que cadastrou o item');
            
            // Índices
            $table->index('status_item');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estoque_interno');
    }
};
