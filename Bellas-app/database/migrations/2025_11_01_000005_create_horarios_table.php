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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profesional_id');
            $table->tinyInteger('dia_semana')->unsigned(); // 0 = domingo .. 6 = sábado
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->timestamps();

            $table->foreign('profesional_id')->references('id')->on('profesionales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('horarios', function (Blueprint $table) {
            $table->dropForeign(['profesional_id']);
        });
        Schema::dropIfExists('horarios');
    }
};
