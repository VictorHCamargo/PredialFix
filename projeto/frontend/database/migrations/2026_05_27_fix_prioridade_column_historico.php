<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Corrigir coluna prioridade de integer para string
     */
    public function up(): void
    {
        Schema::table('historico_status_chamados', function (Blueprint $table) {
            // Alterar coluna prioridade de integer para string (varchar)
            $table->string('prioridade', 50)->nullable()->change()->comment('Prioridade: baixa, media, alta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('historico_status_chamados', function (Blueprint $table) {
            // Reverter para integer
            $table->integer('prioridade')->nullable()->change();
        });
    }
};
