<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhasesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('phases', function (Blueprint $table) {
            $table->uuid('id')->primary('id');
            $table->string('name')->nullable();
            $table->integer('order')->nullable();
            $table->date('due_date')->nullable();
            $table->uuid('project_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('phases');
    }
}
