<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFilesTableAddProjectId extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->integer('project_id')->after('ticket_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });
    }
}
