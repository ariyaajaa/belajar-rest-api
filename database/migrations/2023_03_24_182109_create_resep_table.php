<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResepTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resep', function (Blueprint $table) {
            $table->id('idresep');
            $table->string('judul');
            $table->string('gambar');
            $table->text('cara_pembuatan')->nullable();
            $table->string('video')->nullable();
            $table->string('user_email');
            $table->foreign('user_email')->references('email')->on('user')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status_resep',['draft','submit','publish','unpublished'])->default('draft');
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
        Schema::dropIfExists('resep');
    }
}
