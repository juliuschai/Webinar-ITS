<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategorisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('nama');
        });

        DB::table('kategoris')->insert([
            ['nama' => 'Webinar/Open Talk'],
            ['nama' => 'Konferensi Internasional/Nasional'],
            ['nama' => 'Training/Workshop/Pelatihan'],
        ]);

        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedTinyInteger('kategori_id')->nullable()->after('kategori_acara');
            $table->foreign('kategori_id')->references('id')->on('kategoris')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->dropColumn('kategori_acara');
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
            $table->string('kategori_acara')->after('kategori_id');
            $table->dropColumn('kategori_id');
        });

        Schema::dropIfExists('kategoris');
    }
}
