<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuickreplyMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('messages', 'response_quick_reply')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->text('response_quick_reply')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('messages', 'response_quick_reply')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->dropColumn('response_quick_reply');
            });
        }
    }
}
