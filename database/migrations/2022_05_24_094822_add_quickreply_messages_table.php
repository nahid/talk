<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
Use Illuminate\Database\Schema\Blueprint;

class AddQuickreplyMessagesTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->text('response_quick_reply')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     * 
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->text('response_quick_reply');
        });
    }
}
