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
        Schema::create('profesional_servicio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('profesional_id');
            $table->unsignedBigInteger('servicio_id');
            $table->timestamps();

            $table->foreign('profesional_id')->references('id')->on('profesionales')->onDelete('cascade');
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
            $table->unique(['profesional_id', 'servicio_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profesional_servicio', function (Blueprint $table) {
            $table->dropForeign(['profesional_id']);
            $table->dropForeign(['servicio_id']);
        });
        Schema::dropIfExists('profesional_servicio');
    }
};
