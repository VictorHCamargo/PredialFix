<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notificacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')
                ->constrained('usuarios', 'id_usuario')
                ->onDelete('cascade');
            $table->text('mensagem');
            $table->string('tipo', 50)->default('geral');
            $table->boolean('lida')->default(false);
            $table->unsignedBigInteger('id_chamado')->nullable();
            $table->timestamps();

            $table->index(['id_usuario', 'lida']);
            $table->index('id_chamado');
        });
    }

    public function down(): void {
        Schema::dropIfExists('notificacoes');
    }
};
