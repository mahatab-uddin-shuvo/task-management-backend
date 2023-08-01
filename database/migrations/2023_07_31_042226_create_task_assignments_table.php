<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_assignments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger("task_id")->unsigned()->index();
            $table->bigInteger("assignee")->unsigned()->index();
            $table->bigInteger("assign_for")->unsigned()->index();
            $table->foreign('task_id')->references("id")->on('task_creations');
            $table->foreign('assignee')->references("id")->on('users');
            $table->foreign('assign_for')->references("id")->on('users');
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
        Schema::dropIfExists('task_assignments');
    }
}
