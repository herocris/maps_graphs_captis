<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecomisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decomisos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('observacion');
            $table->string('direccion');
            $table->unsignedBigInteger('municipio_id');
            $table->unsignedBigInteger('institucion_id');

            $table->foreign('municipio_id')
                  ->references('id')->on('municipios')
                  ->onDelete('cascade');
            $table->foreign('institucion_id')
                  ->references('id')->on('institucions')
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
        Schema::dropIfExists('decomisos');
    }
}
