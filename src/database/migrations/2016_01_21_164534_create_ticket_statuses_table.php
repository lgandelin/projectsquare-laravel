<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketStatusesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('ticket_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('ticket_statuses');
    }
}
