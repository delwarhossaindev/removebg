<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_details', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('kpi_main_id');
            $table->string('code');
            $table->double('target');
            $table->double('achievement');
            $table->integer('type');
            $table->timestamps();

            // Foreign key constraints (optional, based on references)
            // $table->foreign('objective_id')->references('id')->on('objectives')->onDelete('cascade');
            // $table->foreign('bsc_perspective_id')->references('id')->on('bsc_perspectives')->onDelete('cascade');
            // $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');
            // $table->foreign('frequency_id')->references('id')->on('frequencies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kpi_details');
    }
}
