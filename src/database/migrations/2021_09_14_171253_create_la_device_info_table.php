<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaDeviceInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('la_device_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->nullable();
            $table->string('os')->nullable();
            $table->string('browser')->nullable();
            $table->string('version')->nullable();
            $table->string('language')->nullable();
            $table->string('user_agent')->nullable();

            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('continent')->nullable();
            $table->string('timezone')->nullable();

            $table->bigInteger('conversion_id')->unsigned();
            $table->foreign('conversion_id')->references('id')
                ->on('la_conversions')->onUpdate('cascade')
                ->onDelete('cascade');

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
        Schema::dropIfExists('la_device_info');
    }
}
