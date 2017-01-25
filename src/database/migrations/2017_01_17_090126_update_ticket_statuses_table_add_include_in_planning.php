<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTicketStatusesTableAddIncludeInPlanning extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_statuses', function (Blueprint $table) {
            $table->boolean('include_in_planning')->after('name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_statuses', function (Blueprint $table) {
            $table->dropColumn('include_in_planning');
        });
    }
}
