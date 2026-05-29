<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('chamados', function (Blueprint $table) {
            $table->string('id_patrimonio', 100)->nullable()->after('descricao');
            $table->index('id_patrimonio');
        });
    }

    public function down(): void {
        Schema::table('chamados', function (Blueprint $table) {
            $table->dropIndex(['id_patrimonio']);
            $table->dropColumn('id_patrimonio');
        });
    }
};
