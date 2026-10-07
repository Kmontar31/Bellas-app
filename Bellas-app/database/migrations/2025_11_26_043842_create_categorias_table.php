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
        Schema::create('categorias', function (Blueprint $table) {
            
            // Campo 'id' autoincrementable y clave primaria
            $table->id(); 
            
            // Campo para el nombre o descripción de la categoría (ejemplo)
            $table->string('nombre'); 

            // Campo para la clave foránea (UNSIGNED para coincidir con el 'id' de 'servicios')
            $table->foreignId('id_servicio')
                  ->constrained('servicios') // 💡 Asegura que la tabla de destino se llama 'servicios'
                  ->onUpdate('cascade') 
                  ->onDelete('cascade'); // Define el comportamiento al eliminar un servicio

            // Campos para las marcas de tiempo (created_at y updated_at)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
