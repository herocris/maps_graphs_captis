<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institucions_decomisos', function (Blueprint $table) {
            $table->bigIncrements('id'); 
                   
            $table->unsignedBigInteger('decomiso_id');
            $table->foreign('decomiso_id')
                  ->references('id')
                  ->on('decomisos')->onDelete('cascade');

            $table->unsignedBigInteger('institucion_id');
            $table->foreign('institucion_id')
                  ->references('id')
                  ->on('institucions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('institucions_decomisos');
    }
};
