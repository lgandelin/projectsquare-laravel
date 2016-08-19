<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTasksTableUpdatedEstimatedTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->float('estimated_time_days')->after('estimated_time')->nullable();
            $table->dropColumn('estimated_time');
            $table->float('estimated_time_hours')->after('estimated_time_days')->nullable();
            $table->float('spent_time_days')->after('estimated_time_hours')->nullable();
            $table->float('spent_time_hours')->after('spent_time_days')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->float('estimated_time')->after('estimated_time_hours');
            $table->dropColumn('estimated_time_days');
            $table->dropColumn('estimated_time_hours');
            $table->dropColumn('spent_time_days');
            $table->dropColumn('spent_time_hours');
        });
    }
}
