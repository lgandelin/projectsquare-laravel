<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateToDoTableAddUserId extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('to_do', function (Blueprint $table) {
            $table->string('user_id')->after('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('to_do', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}
