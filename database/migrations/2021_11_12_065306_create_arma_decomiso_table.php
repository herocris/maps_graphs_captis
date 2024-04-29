<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArmaDecomisoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arma_decomiso', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');

            $table->unsignedBigInteger('decomiso_id');
            $table->unsignedBigInteger('arma_id');

            $table->foreign('decomiso_id')
                  ->references('id')->on('decomisos')
                  ->onDelete('cascade');
            $table->foreign('arma_id')
                  ->references('id')->on('armas')
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
        Schema::dropIfExists('arma_decomiso');
    }
}
