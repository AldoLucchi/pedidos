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
        Schema::table('delivery_rates', function (Blueprint $table) {
            $table->renameColumn('rate', 'delivery_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('delivery_rates', function (Blueprint $table) {
            $table->renameColumn('delivery_rate', 'rate');
        });
    }
};
