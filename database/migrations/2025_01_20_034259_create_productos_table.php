<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion');
            $table->decimal('precio', 8, 2); // Para manejar precios con dos decimales
            $table->string('imagen')->nullable(); // Para la ruta de la imagen
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('productos');
    }    
};
