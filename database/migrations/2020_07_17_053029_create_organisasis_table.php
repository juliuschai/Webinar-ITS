<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganisasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organisasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->index('nama');
            $table->unsignedTinyInteger('org_type_id');
            $table->foreign('org_type_id')->references('id')->on('org_types')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });

        DB::table('organisasis')->insert([
            ['nama' => 'FTIK', 'org_type_id' => 2],
            ['nama' => 'FTE', 'org_type_id' => 2],
            ['nama' => 'Informatika', 'org_type_id' => 1],
            ['nama' => 'Sistem Informasi', 'org_type_id' => 1],
            ['nama' => 'Teknologi Informasi', 'org_type_id' => 1],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organisasis');
    }
}
