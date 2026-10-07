<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bloqueos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->foreignId('profesional_id')->nullable()->constrained('profesionales')->onDelete('cascade');
            $table->string('motivo')->nullable();
            $table->timestamps();
            
            // Un profesional solo puede tener un bloqueo por fecha
            $table->unique(['fecha', 'profesional_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bloqueos');
    }
};
