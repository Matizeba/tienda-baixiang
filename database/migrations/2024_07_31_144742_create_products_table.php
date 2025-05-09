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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); 
            $table->string('description');
            $table->tinyInteger('userId')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->tinyInteger('status')->default(1);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Relación con usuarios
            $table->timestamps();
        });

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre del tipo de unidad (unidad, paquete, caja, etc.)
            $table->text('description')->nullable(); // Descripción para especificar cantidad por paquete, caja, etc.
            $table->timestamps();
        });

        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Relación con productos
            $table->foreignId('unit_id')->constrained('units')->onDelete('cascade'); // Relación con unidades
            $table->decimal('price', 8, 2); // Precio según la unidad
            $table->integer('stock')->default(0); // Cantidad en stock para esa unidad
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('products');
        Schema::dropIfExists('products');
        Schema::dropIfExists('products');
    }
};
