<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaggedMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tagged_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('message_id')->unsigned()->unique();
            $table->integer('tag_from_id')->unsigned()->nullable();
            $table->integer('tag_to_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('message_id')->references('id')->on('messages');

            $table->foreign('tag_from_id')->references('id')->on('tags');
            $table->foreign('tag_to_id')->references('id')->on('tags');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tagged_messages');
    }
}
