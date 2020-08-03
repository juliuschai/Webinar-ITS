<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateHostAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create host account table
        Schema::create('host_accounts', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('nama', 20);
            $table->string('zoom_id');
            $table->string('zoom_email');
            $table->string('pass')->default('pass');
            $table->boolean('type_banyak')->comment('Apakah akun ini untuk peserta 501-1000?');
        });

        DB::table('host_accounts')->insert([
            ['nama' => '500 (1)', 'zoom_id' => '9BRR5m6HQoGbZaJ1YBbbGw', 'zoom_email' => 'webinar500_1@its.ac.id', 'type_banyak' => false],
            ['nama' => '500 (2)', 'zoom_id' => 'JrKkwz4jTyWVUd-UdQP7ow', 'zoom_email' => 'webinar500_2@its.ac.id', 'type_banyak' => false],
            ['nama' => '1000 (1)', 'zoom_id' => '4LvSjxSbRQOSlgFLdLGPGA', 'zoom_email' => 'webinar1000@its.ac.id',  'type_banyak' => true],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('host_accounts');
    }
}
