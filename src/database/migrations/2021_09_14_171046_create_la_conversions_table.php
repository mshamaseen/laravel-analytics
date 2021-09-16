<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaConversionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('la_conversions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('weight')->default(100);
            $table->boolean('is_unique')->default(false);
            $table->string('hash');

            $table->string('url')->nullable();
            $table->string('source')->nullable();
            $table->nullableMorphs('conversionable');
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
        Schema::dropIfExists('la_conversions');
    }
}
