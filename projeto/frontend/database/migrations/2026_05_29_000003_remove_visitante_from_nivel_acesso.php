<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        DB::table('usuarios')
            ->where('nivel_acesso', 'visitante')
            ->update(['nivel_acesso' => 'professor']);

        Schema::table('usuarios', function (Blueprint $table) {
            $table->enum('nivel_acesso', [
                'administrador',
                'tecnico_manutencao',
                'professor',
            ])->default('professor')->change();
        });
    }

    public function down(): void {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->enum('nivel_acesso', [
                'administrador',
                'tecnico_manutencao',
                'professor',
                'visitante',
            ])->default('visitante')->index()->change();
        });
    }
};
