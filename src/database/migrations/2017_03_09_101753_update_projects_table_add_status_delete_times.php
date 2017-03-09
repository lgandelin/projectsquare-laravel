<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectsTableAddStatusDeleteTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->integer('status_id')->after('color')->nullable();
            $table->dropColumn('tasks_scheduled_time');
            $table->dropColumn('tickets_scheduled_time');
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
            $table->dropColumn('status_id');
            $table->float('tasks_scheduled_time')->after('color')->nullable();
            $table->float('tickets_scheduled_time')->after('tasks_scheduled_time')->nullable();
        });
    }
}
