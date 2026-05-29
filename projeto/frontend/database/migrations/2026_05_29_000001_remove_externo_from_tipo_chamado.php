<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        DB::table('chamados')
            ->where('tipo_chamado', 'externo')
            ->update(['tipo_chamado' => 'interno']);

        Schema::table('chamados', function (Blueprint $table) {
            $table->enum('tipo_chamado', ['interno'])->default('interno')->change();
        });
    }

    public function down(): void {
        Schema::table('chamados', function (Blueprint $table) {
            $table->enum('tipo_chamado', ['interno', 'externo'])->default('externo')->change();
        });
    }
};
