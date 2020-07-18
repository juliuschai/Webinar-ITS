<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateBookingsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bookings', function (Blueprint $table) {
			$table->id();
			$table->string('nama_pic');
			$table->string('integra_pic', 20);
			$table->string('email_pic');
			$table->string('sivitas');
			$table->string('unit');
			$table->string('no_wa');
			$table->string('nama_acara');
			$table->unsignedBigInteger('unit_id')->comment('Field Penyelenggara Acara');
			$table->foreign('unit_id')->references('id')->on('units')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->timestamp('waktu_mulai', 0)->default('2000-01-01 00:00');
			$table->timestamp('waktu_akhir', 0)->default('2000-01-01 00:00');
			$table->unsignedBigInteger('user_id');
			$table->foreign('user_id')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->boolean('relay_ITSTV');
			$table->boolean('peserta_banyak')->comment('Apakah peserta lebih dari 500');
			$table->string('api_host_nama')->nullable();
			$table->string('api_host_email')->nullable();
			$table->boolean('disetujui')->nullable();
			$table->string('deskripsi_disetujui')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('bookings');
	}
}
