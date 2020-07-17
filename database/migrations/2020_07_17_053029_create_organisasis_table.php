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
            ['nama' => "Fakultas Sains dan Analitika Data", 'org_type_id' => 2],
            ['nama' => "Fisika", 'org_type_id' => 1],
            ['nama' => "Matematika", 'org_type_id' => 1],
            ['nama' => "Statistika", 'org_type_id' => 1],
            ['nama' => "Kimia", 'org_type_id' => 1],
            ['nama' => "Biologi", 'org_type_id' => 1],
            ['nama' => "Aktuaria", 'org_type_id' => 1],

            ['nama' => "Fakultas Teknologi Industri dan Rekayasa Sistem", 'org_type_id' => 2],
            ['nama' => "Teknik Mesin", 'org_type_id' => 1],
            ['nama' => "Teknik Kimia", 'org_type_id' => 1],
            ['nama' => "Teknik Fisika", 'org_type_id' => 1],
            ['nama' => "Teknik Industri", 'org_type_id' => 1],
            ['nama' => "Teknik Material", 'org_type_id' => 1],

            ['nama' => "Fakultas Teknik Sipil, Perencanaan dan Kebumian", 'org_type_id' => 2],
            ['nama' => "Teknik Sipil", 'org_type_id' => 1],
            ['nama' => "Arsitektur", 'org_type_id' => 1],
            ['nama' => "Teknik Lingkungan", 'org_type_id' => 1],
            ['nama' => "Perencanaan Wilayah Kota", 'org_type_id' => 1],
            ['nama' => "Teknik Geomatika", 'org_type_id' => 1],
            ['nama' => "Teknik Geofisika", 'org_type_id' => 1],

            ['nama' => "Fakultas Teknologi Kelautan", 'org_type_id' => 2],
            ['nama' => "Teknik Perkapalan", 'org_type_id' => 1],
            ['nama' => "Teknik Sistem Perkapalan", 'org_type_id' => 1],
            ['nama' => "Teknik Kelautan", 'org_type_id' => 1],
            ['nama' => "Teknik Transportasi Laut", 'org_type_id' => 1],

            ['nama' => "Fakultas Teknologi Elektro dan Informatika Cerdas", 'org_type_id' => 2],
            ['nama' => "Teknik Elektro", 'org_type_id' => 1],
            ['nama' => "Teknik Biomedik", 'org_type_id' => 1],
            ['nama' => "Teknik Komputer", 'org_type_id' => 1],
            ['nama' => "Teknik Informatika", 'org_type_id' => 1],
            ['nama' => "Sistem Informasi", 'org_type_id' => 1],
            ['nama' => "Teknologi Informasi", 'org_type_id' => 1],

            ['nama' => "Fakultas Desain Kreatif dan Bisnis Digital", 'org_type_id' => 2],
            ['nama' => "Desain Produk Industri", 'org_type_id' => 1],
            ['nama' => "Desain Interior", 'org_type_id' => 1],
            ['nama' => "Desain Komunikasi Visual", 'org_type_id' => 1],
            ['nama' => "Manajemen Bisnis", 'org_type_id' => 1],
            ['nama' => "Studi Pembangunan", 'org_type_id' => 1],
            
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
