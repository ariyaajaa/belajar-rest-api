<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating', function (Blueprint $table) {
            $table->id('idrating');
            $table->integer('rating');
            $table->string('review')->nullable();
            $table->unsignedBigInteger('resep_idresep');
            $table->foreign('resep_idresep')->references('idresep')->on('resep')->onDelete('cascade')->onUpdate('cascade');
            $table->string('email_user');
            $table->foreign('email_user')->references('email')->on('user')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rating');
    }
}
