<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NoWaFromOidUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('no_wa');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_wa')->after('integra');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('no_wa')->after('user_id');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('no_wa');
        });
    }
}
