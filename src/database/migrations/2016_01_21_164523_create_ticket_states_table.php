<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketStatesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ticket_states', function (Blueprint $table) {
            $table->uuid('id');
            $table->integer('ticket_id')->nullable();
            $table->integer('author_user_id')->nullable();
            $table->integer('allocated_user_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('priority')->nullable();
            $table->date('due_date')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('ticket_states');
    }
}
