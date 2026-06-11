<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Verificar e corrigir notificacoes se existir
        try {
            DB::statement('ALTER TABLE `notificacoes` DROP FOREIGN KEY `notificacoes_id_chamado_foreign`;');
            DB::statement('
                ALTER TABLE `notificacoes` 
                ADD CONSTRAINT `notificacoes_id_chamado_foreign` 
                FOREIGN KEY (`id_chamado`) REFERENCES `chamados` (`id_chamado`) 
                ON DELETE CASCADE ON UPDATE CASCADE;
            ');
        } catch (\Exception $e) {
            // Tabela ou constraint não existe, ignorar
        }

        // Verificar e corrigir historico_chamados se existir
        try {
            DB::statement('ALTER TABLE `historico_chamados` DROP FOREIGN KEY `historico_chamados_id_chamado_foreign`;');
            DB::statement('
                ALTER TABLE `historico_chamados` 
                ADD CONSTRAINT `historico_chamados_id_chamado_foreign` 
                FOREIGN KEY (`id_chamado`) REFERENCES `chamados` (`id_chamado`) 
                ON DELETE CASCADE ON UPDATE CASCADE;
            ');
        } catch (\Exception $e) {
            // Tabela ou constraint não existe, ignorar
        }

        // Verificar e corrigir atualizacoes_chamados se existir
        try {
            DB::statement('ALTER TABLE `atualizacoes_chamados` DROP FOREIGN KEY `atualizacoes_chamados_id_chamado_foreign`;');
            DB::statement('
                ALTER TABLE `atualizacoes_chamados` 
                ADD CONSTRAINT `atualizacoes_chamados_id_chamado_foreign` 
                FOREIGN KEY (`id_chamado`) REFERENCES `chamados` (`id_chamado`) 
                ON DELETE CASCADE ON UPDATE CASCADE;
            ');
        } catch (\Exception $e) {
            // Tabela ou constraint não existe, ignorar
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            DB::statement('ALTER TABLE `notificacoes` DROP FOREIGN KEY `notificacoes_id_chamado_foreign`;');
            DB::statement('
                ALTER TABLE `notificacoes` 
                ADD CONSTRAINT `notificacoes_id_chamado_foreign` 
                FOREIGN KEY (`id_chamado`) REFERENCES `chamados` (`id_chamado`);
            ');
        } catch (\Exception $e) {
            // Ignorar
        }

        try {
            DB::statement('ALTER TABLE `historico_chamados` DROP FOREIGN KEY `historico_chamados_id_chamado_foreign`;');
            DB::statement('
                ALTER TABLE `historico_chamados` 
                ADD CONSTRAINT `historico_chamados_id_chamado_foreign` 
                FOREIGN KEY (`id_chamado`) REFERENCES `chamados` (`id_chamado`);
            ');
        } catch (\Exception $e) {
            // Ignorar
        }

        try {
            DB::statement('ALTER TABLE `atualizacoes_chamados` DROP FOREIGN KEY `atualizacoes_chamados_id_chamado_foreign`;');
            DB::statement('
                ALTER TABLE `atualizacoes_chamados` 
                ADD CONSTRAINT `atualizacoes_chamados_id_chamado_foreign` 
                FOREIGN KEY (`id_chamado`) REFERENCES `chamados` (`id_chamado`);
            ');
        } catch (\Exception $e) {
            // Ignorar
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
