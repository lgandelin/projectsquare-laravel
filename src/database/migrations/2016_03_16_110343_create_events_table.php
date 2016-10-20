<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('user_id');
            $table->string('name');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->uuid('ticket_id')->nullable();
            $table->uuid('project_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('events');
    }
}
