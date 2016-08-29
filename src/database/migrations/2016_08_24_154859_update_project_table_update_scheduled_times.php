<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectTableUpdateScheduledTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('scheduled_time');
            $table->float('tasks_scheduled_time')->after('color')->nullable();
            $table->float('tickets_scheduled_time')->after('tasks_scheduled_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->float('scheduled_time')->after('color')->nullable();
            $table->dropColumn('tasks_scheduled_time');
            $table->dropColumn('tickets_scheduled_time');
        });
    }
}
