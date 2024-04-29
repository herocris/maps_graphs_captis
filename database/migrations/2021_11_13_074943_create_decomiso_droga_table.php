<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDecomisoDrogaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decomiso_droga', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->double('peso');

            $table->unsignedBigInteger('decomiso_id');
            $table->unsignedBigInteger('droga_id');
            $table->unsignedBigInteger('presentacion_droga_id');

            $table->foreign('decomiso_id')
                  ->references('id')->on('decomisos')
                  ->onDelete('cascade');
            $table->foreign('droga_id')
                  ->references('id')->on('drogas')
                  ->onDelete('cascade'); 
            $table->foreign('presentacion_droga_id')
                  ->references('id')->on('presentacion_drogas')
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
        Schema::dropIfExists('decomiso_droga');
    }
}
