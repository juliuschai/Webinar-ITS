<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCivitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('civitas', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('nama', 12);
        });
        DB::table('civitas')->insert([
            ['nama' => 'Dosen'],
            ['nama' => 'Tendik'],
            ['nama' => 'Mahasiswa'],
            ['nama' => 'Lain-lain'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('civitas');
    }
}
