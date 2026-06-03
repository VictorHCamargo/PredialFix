<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        DB::table('usuarios')
            ->where('nivel_acesso', 'visitante')
            ->update(['nivel_acesso' => 'aluno']);

        Schema::table('usuarios', function (Blueprint $table) {
            $table->enum('nivel_acesso', [
                'administrador',
                'gerente_manutencao',
                'tecnico_manutencao',
                'professor',
                'aluno',
            ])->default('aluno')->change();
        });
    }

    public function down(): void {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->enum('nivel_acesso', [
                'administrador',
                'gerente_manutencao',
                'tecnico_manutencao',
                'professor',
                'aluno',
                'visitante',
            ])->default('visitante')->index()->change();
        });
    }
};
