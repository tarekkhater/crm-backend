<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_notes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('agent_id')->nullable(); // id for row agentUser
            $table->bigInteger('user_id')->nullable();// id for user to send  message
            $table->date('content');
            $table->enum('message_by', ['1', '0'])->nullable()->default(['0']);// 1  of  user &&  0 of agent
            $table->enum('status', ['1', '0'])->nullable()->default(['0']);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('agent_users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agent_notes');
    }
}
