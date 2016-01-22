<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_states', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id')->nullable();
            $table->integer('author_user_id')->nullable();
            $table->integer('allocated_user_id')->nullable();
            $table->integer('ticket_status_id')->nullable();
            $table->string('duration')->nullable();
            $table->integer('priority')->nullable();
            $table->text('comments')->nullable();
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
        Schema::drop('ticket_states');
    }
}
