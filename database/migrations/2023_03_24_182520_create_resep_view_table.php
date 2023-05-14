<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResepViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resep_view', function (Blueprint $table) {
            $table->id('idresep_view');
            $table->string('email')->nullable();
            $table->timestamp('date');
            $table->unsignedBigInteger('resep_idresep');
            $table->foreign('resep_idresep')->references('idresep')->on('resep')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resep_view');
    }
}
