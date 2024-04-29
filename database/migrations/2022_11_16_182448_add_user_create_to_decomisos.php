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
        Schema::table('decomisos', function (Blueprint $table) {
            $table->unsignedBigInteger('user_create')->default(1);
            $table->unsignedBigInteger('user_update')->default(1);

            $table->foreign('user_create')
                  ->references('id')
                  ->on('users');
            $table->foreign('user_update')
                  ->references('id')
                  ->on('users');      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('decomisos', function (Blueprint $table) {
            $table->dropColumn('user_create');
            $table->dropColumn('user_update');
        });
    }
};
