<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecomisoPrecursorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decomiso_precursor', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->double('volumen');

            $table->unsignedBigInteger('decomiso_id');
            $table->unsignedBigInteger('precursor_id');
            $table->unsignedBigInteger('presentacion_precursor_id');

            $table->foreign('decomiso_id')
                  ->references('id')->on('decomisos')
                  ->onDelete('cascade');
            $table->foreign('precursor_id')
                  ->references('id')->on('precursors')
                  ->onDelete('cascade'); 
            $table->foreign('presentacion_precursor_id')
                  ->references('id')->on('presentacion_precursors')
                  ->onDelete('cascade');      
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('decomiso_precursor');
    }
}
