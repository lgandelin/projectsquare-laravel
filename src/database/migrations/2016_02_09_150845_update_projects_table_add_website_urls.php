<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectsTableAddWebsiteUrls extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('site_url');
            $table->string('website_front_url')->after('status')->nullable();
            $table->string('website_back_url')->after('website_front_url')->nullable();
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
            $table->string('site_url')->after('status')->nullable();
            $table->dropColumn('website_front_url');
            $table->dropColumn('website_back_url');
        });
    }
}
