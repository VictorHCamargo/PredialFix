<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_chamado')->constrained('chamados', 'id_chamado');
            $table->integer('nota');
            $table->text('comentario')->nullable();
            $table->date('data_feedback');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('feedbacks');
    }
};
