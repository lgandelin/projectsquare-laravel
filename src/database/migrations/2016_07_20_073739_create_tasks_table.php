<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary('id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('estimated_time')->nullable();
            $table->integer('status_id')->nullable();
            $table->uuid('project_id')->nullable();
            $table->uuid('allocated_user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tasks');
    }
}
