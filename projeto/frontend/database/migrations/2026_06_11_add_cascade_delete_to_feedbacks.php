<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Dropar a constraint antiga
        DB::statement('ALTER TABLE `feedbacks` DROP FOREIGN KEY `feedbacks_id_chamado_foreign`;');

        // Recriar com onDelete('cascade')
        DB::statement('
            ALTER TABLE `feedbacks` 
            ADD CONSTRAINT `feedbacks_id_chamado_foreign` 
            FOREIGN KEY (`id_chamado`) REFERENCES `chamados` (`id_chamado`) 
            ON DELETE CASCADE ON UPDATE CASCADE;
        ');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::statement('ALTER TABLE `feedbacks` DROP FOREIGN KEY `feedbacks_id_chamado_foreign`;');

        DB::statement('
            ALTER TABLE `feedbacks` 
            ADD CONSTRAINT `feedbacks_id_chamado_foreign` 
            FOREIGN KEY (`id_chamado`) REFERENCES `chamados` (`id_chamado`);
        ');

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
