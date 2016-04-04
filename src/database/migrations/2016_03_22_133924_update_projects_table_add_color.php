<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectsTableAddColor extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('color')->after('referer_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
}
