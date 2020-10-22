<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ZoomMeeting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_times', function (Blueprint $table) {
            $table->enum('tipe_zoom', ['webinar', 'meeting'])->default('webinar')->after('gladi');
        });

        Schema::table('host_accounts', function (Blueprint $table) {
            $table->enum('tipe_zoom', ['webinar', 'meeting'])->default('webinar')->after('max_peserta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_times', function (Blueprint $table) {
            $table->dropColumn('tipe_zoom');
        });

        Schema::table('host_accounts', function (Blueprint $table) {
            $table->dropColumn('tipe_zoom');
        });
    }
}
