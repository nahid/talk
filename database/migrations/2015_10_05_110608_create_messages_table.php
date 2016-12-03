<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function ($tbl) {
            $tbl->increments('id');
            $tbl->text('message');
            $tbl->boolean('is_seen')->default(0);
            $tbl->boolean('deleted_from_sender')->default(0);
            $tbl->boolean('deleted_from_receiver')->default(0);
            $tbl->integer('user_id');
            $tbl->integer('conversation_id');
            $tbl->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('messages');
    }
}
