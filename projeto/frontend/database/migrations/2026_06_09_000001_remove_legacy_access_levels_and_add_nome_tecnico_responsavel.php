<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        DB::table('usuarios')
            ->where('nivel_acesso', 'like', '%manutencao')
            ->where('nivel_acesso', '<>', 'tecnico_manutencao')
            ->update(['nivel_acesso' => 'tecnico_manutencao']);

        DB::table('usuarios')
            ->whereNotIn('nivel_acesso', ['administrador', 'tecnico_manutencao', 'professor'])
            ->update(['nivel_acesso' => 'professor']);

        if (DB::connection()->getDriverName() !== 'sqlite') {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->enum('nivel_acesso', [
                    'administrador',
                    'tecnico_manutencao',
                    'professor',
                ])->default('professor')->change();
            });
        }

        Schema::table('chamados', function (Blueprint $table) {
            if (!Schema::hasColumn('chamados', 'nome_tecnico_responsavel')) {
                $table->string('nome_tecnico_responsavel', 100)->nullable()->after('data_conclusao');
            }
        });
    }

    public function down(): void {
        Schema::table('chamados', function (Blueprint $table) {
            if (Schema::hasColumn('chamados', 'nome_tecnico_responsavel')) {
                $table->dropColumn('nome_tecnico_responsavel');
            }
        });
    }
};
