<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTicketStatesAddEstimatedTime extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('ticket_states', function (Blueprint $table) {
            $table->string('estimated_time')->after('due_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('ticket_states', function (Blueprint $table) {
            $table->dropColumn('estimated_time');
        });
    }
}
