<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDivisionStages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stage_divisions', function (Blueprint $table) {
            $table->id();
            $table->string('stage_name');
            $table->string('location_name');
            $table->string('category_name');
            $table->foreignId('level_id');
            $table->foreignId('location_id');
            $table->foreignId('category_id');
            $table->foreignId('created_by_id'); // user id
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
        Schema::dropIfExists('stage_divisions');
    }
}
