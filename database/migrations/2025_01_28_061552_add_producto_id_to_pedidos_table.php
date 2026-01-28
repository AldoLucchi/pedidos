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
        Schema::table('pedidos', function (Blueprint $table) {
            // Agregar el campo producto_id como una clave foránea
            $table->unsignedBigInteger('producto_id')->nullable()->after('random_value_3');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            // Eliminar la clave foránea y el campo
            $table->dropForeign(['producto_id']);
            $table->dropColumn('producto_id');
        });
    }
};
