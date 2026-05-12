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
        Schema::table('chamados', function (Blueprint $table) {
            if (!Schema::hasColumn('chamados', 'secao_tecnica')) {
                $table->string('secao_tecnica')->nullable()->after('id_tipo');
            }
            if (!Schema::hasColumn('chamados', 'complexidade')) {
                $table->string('complexidade')->nullable()->after('secao_tecnica');
            }
            if (!Schema::hasColumn('chamados', 'tipo_trabalho')) {
                $table->string('tipo_trabalho')->nullable()->after('complexidade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chamados', function (Blueprint $table) {
            $table->dropColumn(['secao_tecnica', 'complexidade', 'tipo_trabalho']);
        });
    }
};
