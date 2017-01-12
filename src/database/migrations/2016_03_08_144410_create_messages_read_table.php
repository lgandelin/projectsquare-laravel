<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesReadTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('messages_read', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('message_id');
            $table->boolean('read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('messages_read');
    }
}
