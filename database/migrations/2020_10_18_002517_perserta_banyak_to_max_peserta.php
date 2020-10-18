<?php

use App\BookingTime;
use App\HostAccount;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PersertaBanyakToMaxPeserta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_times', function (Blueprint $table) {
            $table->smallInteger('max_peserta')->after('peserta_banyak');
        });

        $banyakBools = DB::table('booking_times')->pluck('peserta_banyak', 'id');
        foreach ($banyakBools as $id => $val) {
            $book_time = BookingTime::find($id);
            if ($val == 1) {
                $book_time->max_peserta = 1000;
            } else {
                $book_time->max_peserta = 500;
            }
            $book_time->save();
        }

        Schema::table('booking_times', function (Blueprint $table) {
            $table->dropColumn('peserta_banyak');
        });

        // Start zoom meeting
        Schema::table('host_accounts', function (Blueprint $table) {
            $table->smallInteger('max_peserta')->after('pass');
        });

        $banyakBools = DB::table('host_accounts')->pluck('type_banyak', 'id');
        foreach ($banyakBools as $id => $val) {
            $host_acc = HostAccount::find($id);
            if ($val == 1) {
                $host_acc->max_peserta = 1000;
            } else {
                $host_acc->max_peserta = 500;
            }
            $host_acc->save();
        }

        Schema::table('host_accounts', function (Blueprint $table) {
            $table->dropColumn('type_banyak');
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
            $table->dropColumn('max_peserta');
            $table->boolean('peserta_banyak')->comment('Apakah peserta lebih dari 500')->after('relay_ITSTV');
        });

        Schema::table('host_accounts', function (Blueprint $table) {
            $table->dropColumn('max_peserta');
            $table->boolean('type_banyak')->comment('Apakah akun ini untuk peserta 501-1000?');
        });
    }
}
