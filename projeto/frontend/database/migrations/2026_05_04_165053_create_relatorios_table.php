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
        Schema::create('relatorios', function (Blueprint $table) {
            $table->id('id_relatorio');
            $table->string('titulo');
            $table->string('tipo_relatorio');
            $table->date('data_relatorio');
            $table->enum('prioridade', ['baixa', 'media', 'alta']);
            $table->string('status');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relatorios');
    }
};
