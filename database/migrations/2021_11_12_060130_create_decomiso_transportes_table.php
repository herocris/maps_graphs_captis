<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecomisoTransportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decomiso_transportes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('marca');
            $table->string('modelo');
            $table->string('color');
            $table->string('placa');

            $table->unsignedBigInteger('decomiso_id');
            $table->unsignedBigInteger('pais_pro_id');
            $table->unsignedBigInteger('pais_des_id');
            $table->unsignedBigInteger('dep_pro_id');
            $table->unsignedBigInteger('dep_des_id');
            $table->unsignedBigInteger('mun_pro_id');
            $table->unsignedBigInteger('mun_des_id');

            $table->foreign('decomiso_id')
                  ->references('id')->on('decomisos')
                  ->onDelete('cascade');
            $table->foreign('pais_pro_id')
                  ->references('id')->on('pais')
                  ->onDelete('cascade');
            $table->foreign('pais_des_id')
                  ->references('id')->on('pais')
                  ->onDelete('cascade');
            $table->foreign('dep_pro_id')
                  ->references('id')->on('departamentos')
                  ->onDelete('cascade');
            $table->foreign('dep_des_id')
                  ->references('id')->on('departamentos')
                  ->onDelete('cascade');
            $table->foreign('mun_pro_id')
                  ->references('id')->on('municipios')
                  ->onDelete('cascade');
            $table->foreign('mun_des_id')
                  ->references('id')->on('municipios')
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
        Schema::dropIfExists('decomiso_transportes');
    }
}
