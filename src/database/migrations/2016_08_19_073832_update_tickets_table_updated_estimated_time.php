<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTicketsTableUpdatedEstimatedTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ticket_states', function (Blueprint $table) {
            $table->dropColumn('estimated_time');
            $table->double('estimated_time_days', 8, 3)->after('estimated_time')->nullable();
            $table->double('estimated_time_hours', 8, 3)->after('estimated_time_days')->nullable();
            $table->double('spent_time_days', 8, 3)->after('estimated_time_hours')->nullable();
            $table->double('spent_time_hours', 8, 3)->after('spent_time_days')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket_states', function (Blueprint $table) {
            $table->float('estimated_time')->after('estimated_time_hours');
            $table->dropColumn('estimated_time_days');
            $table->dropColumn('estimated_time_hours');
            $table->dropColumn('spent_time_days');
            $table->dropColumn('spent_time_hours');
        });
    }
}
