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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('cliente_nombre');
            $table->string('cliente_apellido');
            $table->string('cliente_telefono');
            $table->integer('garrafones_pedidos');
            $table->string('cliente_direccion');
            $table->string('cliente_estado');
            $table->timestamp('fecha_pedido');
            $table->string('metodo_pago');
            $table->text('notas');
            $table->string('random_value_1');
            $table->string('random_value_2');
            $table->string('random_value_3');            
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
