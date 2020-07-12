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
			$table->string('nama_acara');
			$table->string('unit');
			$table->string('nama_booker');
			$table->string('email_its');
			$table->string('user_integra');
			$table->timestamp('waktu_mulai', 0)->default('2000-01-01 00:00');
			$table->timestamp('waktu_akhir', 0)->default('2000-01-01 00:00');
			$table->unsignedTinyInteger('civitas_id');
			$table->foreign('civitas_id')->references('id')->on('civitas')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->boolean('relay_ITSTV');
			$table->boolean('peserta_banyak')->comment('Apakah peserta lebih dari 500');
			$table->string('api_host_name')->nullable();
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
