<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProjectsTableAddWebsiteUrls extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('site_url');
            $table->string('website_front_url')->after('client_id')->nullable();
            $table->string('website_back_url')->after('website_front_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('site_url')->after('client_id')->nullable();
            $table->dropColumn('website_front_url');
            $table->dropColumn('website_back_url');
        });
    }
}
