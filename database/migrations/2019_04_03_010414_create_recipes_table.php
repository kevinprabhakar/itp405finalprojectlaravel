<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('Title');
            $table->string('FoodType');
            $table->float('Calories');
            $table->float('Fat');
            $table->float('Cholesterol');
            $table->float('Sugar');
            $table->float('Protein');
            $table->float('Sodium');
            $table->float('Carbs');
            $table->integer('UserId');
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
        Schema::dropIfExists('recipes');
    }
}
