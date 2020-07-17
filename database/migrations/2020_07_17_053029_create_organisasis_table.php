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
            ['nama' => 'Fakultas Teknologi Kelautan', 'org_type_id' => 2],

            ['nama' => 'Informatika', 'org_type_id' => 1],

            ['nama' => "Kantor Penjaminan Mutu", 'org_type_id' => 3],
            ['nama' => "Kantor Audit Internal", 'org_type_id' => 3],
            ['nama' => "Sekretaris Institut", 'org_type_id' => 3],
            ['nama' => "Direktorat Kemitraan Global", 'org_type_id' => 3],
            ['nama' => "Direktorat Pendidikan", 'org_type_id' => 3],
            ['nama' => "Direktorat Pascasarjana dan Pengembangan Akademik", 'org_type_id' => 3],
            ['nama' => "Direktorat Kemahasiswaan", 'org_type_id' => 3],
            ['nama' => "Perpustakaan", 'org_type_id' => 3],
            ['nama' => "Direktorat Perencanaan dan Pengembangan", 'org_type_id' => 3],
            ['nama' => "Biro Sarana dan Prasarana", 'org_type_id' => 3],
            ['nama' => "Biro Keuangan", 'org_type_id' => 3],
            ['nama' => "Direktorat SDM Organisasi", 'org_type_id' => 3],
            ['nama' => "Biro Umum dan Reformasi Birokrasi", 'org_type_id' => 3],
            ['nama' => "Direktorat Pengembangan Teknologi dan Sistem Informasi", 'org_type_id' => 3],
            ['nama' => "Direktorat Riset dan Pengabdian kepada Masyarakat", 'org_type_id' => 3],
            ['nama' => "Direktorat Inovasi dan Kawasan Sains Teknologi", 'org_type_id' => 3],
            ['nama' => "Direktorat Kerjasama dan Pengelola Usaha", 'org_type_id' => 3],
            ['nama' => "Unit Pengembangan Smart Eco Campus", 'org_type_id' => 3],
            ['nama' => "UKPBJ", 'org_type_id' => 3],
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
