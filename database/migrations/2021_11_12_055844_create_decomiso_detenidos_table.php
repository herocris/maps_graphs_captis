<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecomisoDetenidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decomiso_detenidos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('alias');
            $table->string('identidad');
            $table->integer('edad');

            $table->unsignedBigInteger('decomiso_id');
            $table->unsignedBigInteger('identificacion_id');
            $table->unsignedBigInteger('estructura_id');
            $table->unsignedBigInteger('ocupacion_id');
            $table->unsignedBigInteger('estado_civil_id');
            $table->unsignedBigInteger('pais_id');

            $table->foreign('decomiso_id')
                  ->references('id')->on('decomisos')
                  ->onDelete('cascade');
            $table->foreign('identificacion_id')
                  ->references('id')->on('identificacions')
                  ->onDelete('cascade');
            $table->foreign('estructura_id')
                  ->references('id')->on('estructura_criminals')
                  ->onDelete('cascade');
            $table->foreign('ocupacion_id')
                  ->references('id')->on('ocupacions')
                  ->onDelete('cascade');
            $table->foreign('estado_civil_id')
                  ->references('id')->on('estado_civils')
                  ->onDelete('cascade');
            $table->foreign('pais_id')
                  ->references('id')->on('pais')
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
        Schema::dropIfExists('decomiso_detenidos');
    }
}
