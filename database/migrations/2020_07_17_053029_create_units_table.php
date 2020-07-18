<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->index('nama');
            $table->unsignedTinyInteger('unit_type_id');
            $table->foreign('unit_type_id')->references('id')->on('unit_types')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });

        DB::table('units')->insert([
            ['nama' => "Fakultas Sains dan Analitika Data", 'unit_type_id' => 2],
            ['nama' => "Fisika", 'unit_type_id' => 1],
            ['nama' => "Matematika", 'unit_type_id' => 1],
            ['nama' => "Statistika", 'unit_type_id' => 1],
            ['nama' => "Kimia", 'unit_type_id' => 1],
            ['nama' => "Biologi", 'unit_type_id' => 1],
            ['nama' => "Aktuaria", 'unit_type_id' => 1],

            ['nama' => "Fakultas Teknologi Industri dan Rekayasa Sistem", 'unit_type_id' => 2],
            ['nama' => "Teknik Mesin", 'unit_type_id' => 1],
            ['nama' => "Teknik Kimia", 'unit_type_id' => 1],
            ['nama' => "Teknik Fisika", 'unit_type_id' => 1],
            ['nama' => "Teknik Industri", 'unit_type_id' => 1],
            ['nama' => "Teknik Material", 'unit_type_id' => 1],

            ['nama' => "Fakultas Teknik Sipil, Perencanaan dan Kebumian", 'unit_type_id' => 2],
            ['nama' => "Teknik Sipil", 'unit_type_id' => 1],
            ['nama' => "Arsitektur", 'unit_type_id' => 1],
            ['nama' => "Teknik Lingkungan", 'unit_type_id' => 1],
            ['nama' => "Perencanaan Wilayah Kota", 'unit_type_id' => 1],
            ['nama' => "Teknik Geomatika", 'unit_type_id' => 1],
            ['nama' => "Teknik Geofisika", 'unit_type_id' => 1],

            ['nama' => "Fakultas Teknologi Kelautan", 'unit_type_id' => 2],
            ['nama' => "Teknik Perkapalan", 'unit_type_id' => 1],
            ['nama' => "Teknik Sistem Perkapalan", 'unit_type_id' => 1],
            ['nama' => "Teknik Kelautan", 'unit_type_id' => 1],
            ['nama' => "Teknik Transportasi Laut", 'unit_type_id' => 1],

            ['nama' => "Fakultas Teknologi Elektro dan Informatika Cerdas", 'unit_type_id' => 2],
            ['nama' => "Teknik Elektro", 'unit_type_id' => 1],
            ['nama' => "Teknik Biomedik", 'unit_type_id' => 1],
            ['nama' => "Teknik Komputer", 'unit_type_id' => 1],
            ['nama' => "Teknik Informatika", 'unit_type_id' => 1],
            ['nama' => "Sistem Informasi", 'unit_type_id' => 1],
            ['nama' => "Teknologi Informasi", 'unit_type_id' => 1],

            ['nama' => "Fakultas Desain Kreatif dan Bisnis Digital", 'unit_type_id' => 2],
            ['nama' => "Desain Produk Industri", 'unit_type_id' => 1],
            ['nama' => "Desain Interior", 'unit_type_id' => 1],
            ['nama' => "Desain Komunikasi Visual", 'unit_type_id' => 1],
            ['nama' => "Manajemen Bisnis", 'unit_type_id' => 1],
            ['nama' => "Studi Pembangunan", 'unit_type_id' => 1],
            
            ['nama' => "Kantor Penjaminan Mutu", 'unit_type_id' => 3],
            ['nama' => "Kantor Audit Internal", 'unit_type_id' => 3],
            ['nama' => "Sekretaris Institut", 'unit_type_id' => 3],
            ['nama' => "Direktorat Kemitraan Global", 'unit_type_id' => 3],
            ['nama' => "Direktorat Pendidikan", 'unit_type_id' => 3],
            ['nama' => "Direktorat Pascasarjana dan Pengembangan Akademik", 'unit_type_id' => 3],
            ['nama' => "Direktorat Kemahasiswaan", 'unit_type_id' => 3],
            ['nama' => "Perpustakaan", 'unit_type_id' => 3],
            ['nama' => "Direktorat Perencanaan dan Pengembangan", 'unit_type_id' => 3],
            ['nama' => "Biro Sarana dan Prasarana", 'unit_type_id' => 3],
            ['nama' => "Biro Keuangan", 'unit_type_id' => 3],
            ['nama' => "Direktorat SDM Unit", 'unit_type_id' => 3],
            ['nama' => "Biro Umum dan Reformasi Birokrasi", 'unit_type_id' => 3],
            ['nama' => "Direktorat Pengembangan Teknologi dan Sistem Informasi", 'unit_type_id' => 3],
            ['nama' => "Direktorat Riset dan Pengabdian kepada Masyarakat", 'unit_type_id' => 3],
            ['nama' => "Direktorat Inovasi dan Kawasan Sains Teknologi", 'unit_type_id' => 3],
            ['nama' => "Direktorat Kerjasama dan Pengelola Usaha", 'unit_type_id' => 3],
            ['nama' => "Unit Pengembangan Smart Eco Campus", 'unit_type_id' => 3],
            ['nama' => "UKPBJ", 'unit_type_id' => 3],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }
}
