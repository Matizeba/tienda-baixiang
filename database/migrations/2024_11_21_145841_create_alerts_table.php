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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id(); // Identificador único de la alerta
            $table->foreignId('product_unit_id')->constrained('product_units')->onDelete('cascade'); // Relación con las unidades de producto
            $table->string('title'); // Título de la alerta
            $table->text('message'); // Descripción detallada de la alerta
            $table->enum('type', ['info', 'warning', 'error'])->default('warning'); // Tipo de alerta: informativa, advertencia o error
            $table->tinyInteger('status')->default(0); // Estado de la alerta: 0 = no vista, 1 = vista
            $table->timestamps(); // Fechas de creación y actualización de la alerta
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
