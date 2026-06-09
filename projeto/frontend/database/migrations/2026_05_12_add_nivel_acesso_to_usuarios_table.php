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
        Schema::table('usuarios', function (Blueprint $table) {
            $table->enum('nivel_acesso', [
                'administrador',
                'tecnico_manutencao',
                'professor',
                'visitante'
            ])->default('visitante')->index()->comment('Nível de acesso do usuário');
            
            $table->string('setor')->nullable()->comment('Setor/departamento do usuário');
            $table->boolean('ativo')->default(true)->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['nivel_acesso', 'setor', 'ativo']);
        });
    }
};
