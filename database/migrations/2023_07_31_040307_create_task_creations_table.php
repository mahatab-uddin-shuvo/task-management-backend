<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskCreationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_creations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('status', ['Open','In-progress','Done'])->default('Open');
            $table->text('description');
            $table->timestamp('duration');
            $table->bigInteger("created_by")->unsigned()->index();
            $table->foreign('created_by')->references("id")->on('users');
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
        Schema::dropIfExists('task_creations');
    }
}
