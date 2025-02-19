<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiMainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_mains', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('kpi_id');
            $table->unsignedBigInteger('objective_id');
            $table->unsignedBigInteger('perspective_id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('frequency_id');
            $table->integer('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kpi_mains');
    }
}
