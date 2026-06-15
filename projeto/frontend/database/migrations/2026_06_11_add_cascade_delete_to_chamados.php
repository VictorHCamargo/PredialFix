<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        // Desabilitar verificação de foreign keys temporariamente
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Dropar as constraints antigas
        DB::statement('ALTER TABLE `chamados` DROP FOREIGN KEY `chamados_id_usuario_foreign`;');
        DB::statement('ALTER TABLE `chamados` DROP FOREIGN KEY `chamados_id_usuario_responsavel_foreign`;');

        // Recriar com onDelete('cascade') e onDelete('set null')
        DB::statement('
            ALTER TABLE `chamados` 
            ADD CONSTRAINT `chamados_id_usuario_foreign` 
            FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) 
            ON DELETE CASCADE ON UPDATE CASCADE;
        ');

        DB::statement('
            ALTER TABLE `chamados` 
            ADD CONSTRAINT `chamados_id_usuario_responsavel_foreign` 
            FOREIGN KEY (`id_usuario_responsavel`) REFERENCES `usuarios` (`id_usuario`) 
            ON DELETE SET NULL ON UPDATE CASCADE;
        ');

        // Reabilitar verificação de foreign keys
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::statement('ALTER TABLE `chamados` DROP FOREIGN KEY `chamados_id_usuario_foreign`;');
        DB::statement('ALTER TABLE `chamados` DROP FOREIGN KEY `chamados_id_usuario_responsavel_foreign`;');

        DB::statement('
            ALTER TABLE `chamados` 
            ADD CONSTRAINT `chamados_id_usuario_foreign` 
            FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
        ');

        DB::statement('
            ALTER TABLE `chamados` 
            ADD CONSTRAINT `chamados_id_usuario_responsavel_foreign` 
            FOREIGN KEY (`id_usuario_responsavel`) REFERENCES `usuarios` (`id_usuario`);
        ');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};

