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
        Schema::table('servicios', function (Blueprint $table) {
            // Añadir categoria_id FK
            if (!Schema::hasColumn('servicios', 'categoria_id')) {
                $table->unsignedBigInteger('categoria_id')->nullable()->after('nombre');
                $table->foreign('categoria_id')
                      ->references('id')
                      ->on('categorias')
                      ->onDelete('set null')
                      ->onUpdate('cascade');
            }
        });

        Schema::table('categorias', function (Blueprint $table) {
            // Eliminar id_servicio si existe
            if (Schema::hasColumn('categorias', 'id_servicio')) {
                try {
                    $table->dropForeign(['id_servicio']);
                } catch (\Exception $e) {
                    // Ignorar si la FK no existe
                }
                $table->dropColumn('id_servicio');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servicios', function (Blueprint $table) {
            if (Schema::hasColumn('servicios', 'categoria_id')) {
                try {
                    $table->dropForeign(['categoria_id']);
                } catch (\Exception $e) {
                    // Ignorar si la FK no existe
                }
                $table->dropColumn('categoria_id');
            }
        });
    }
};
