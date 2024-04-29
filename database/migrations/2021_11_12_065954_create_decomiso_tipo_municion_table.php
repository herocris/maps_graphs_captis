<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecomisoTipoMunicionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decomiso_tipo_municion', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');

            $table->unsignedBigInteger('decomiso_id');
            $table->unsignedBigInteger('tipo_municion_id');

            $table->foreign('decomiso_id')
                  ->references('id')->on('decomisos')
                  ->onDelete('cascade');
            $table->foreign('tipo_municion_id')
                  ->references('id')->on('tipo_municions')
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
        Schema::dropIfExists('decomiso_tipo_municion');
    }
}
