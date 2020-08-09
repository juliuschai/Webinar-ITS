<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DateTimezoneFix extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('UPDATE bookings SET waktu_mulai = DATE_SUB(waktu_mulai, INTERVAL 7 HOUR), waktu_akhir = DATE_SUB(waktu_akhir, INTERVAL 7 HOUR);');
        DB::statement('UPDATE booking_times SET waktu_mulai = DATE_SUB(waktu_mulai, INTERVAL 7 HOUR), waktu_akhir = DATE_SUB(waktu_akhir, INTERVAL 7 HOUR);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('UPDATE bookings SET waktu_mulai = DATE_ADD(waktu_mulai, INTERVAL 7 HOUR), waktu_akhir = DATE_ADD(waktu_akhir, INTERVAL 7 HOUR);');
        DB::statement('UPDATE booking_times SET waktu_mulai = DATE_ADD(waktu_mulai, INTERVAL 7 HOUR), waktu_akhir = DATE_ADD(waktu_akhir, INTERVAL 7 HOUR);');
    }
}
