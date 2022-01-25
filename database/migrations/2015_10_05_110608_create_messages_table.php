<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
Use Illuminate\Database\Schema\Blueprint;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message');
            $table->boolean('is_seen')->default(0);
            $table->boolean('deleted_from_sender')->default(0);
            $table->boolean('deleted_from_receiver')->default(0);
            $table->integer('user_id');
            $table->integer('conversation_id');
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
        Schema::dropIfExists('messages');
    }
}
