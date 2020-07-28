<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingTimesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('booking_times', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('booking_id');
			$table->foreign('booking_id')->references('id')->on('bookings')->onUpdate('CASCADE')->onDelete('CASCADE');
			$table->timestamp('waktu_mulai', 0)->default('1980-01-01 00:00');
			$table->index('waktu_mulai');
			$table->timestamp('waktu_akhir', 0)->default('1980-01-01 00:00');
			$table->index('waktu_akhir');
			$table->boolean('relay_ITSTV');
			$table->boolean('peserta_banyak')->comment('Apakah peserta lebih dari 500');
			$table->unsignedTinyInteger('host_account_id')->nullable();
			$table->foreign('host_account_id')->references('id')->on('host_accounts')->onUpdate('CASCADE')->onDelete('RESTRICT');
			$table->boolean('disetujui')->nullable();
			$table->timestamps();
		});

		$select = DB::table('bookings')
			->leftJoin('host_accounts', 'host_accounts.nama', '=', 'bookings.api_host_email')
			->select(['bookings.id', 'waktu_mulai', 'waktu_akhir', 'relay_ITSTV', 'peserta_banyak', 
				'host_accounts.id as host_account_id', 'disetujui', 'created_at', 'updated_at'
			]);

		DB::table('booking_times')
			->insertUsing(['booking_id', 'waktu_mulai', 'waktu_akhir', 'relay_ITSTV', 'peserta_banyak', 
				'host_account_id', 'disetujui', 'created_at', 'updated_at'], $select);

		Schema::table('bookings', function (Blueprint $table) {
			$table->dropColumn('api_host_email');
			$table->dropColumn('api_host_nama');
			$table->dropColumn('peserta_banyak');
			$table->dropColumn('relay_ITSTV');
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
			$table->boolean('relay_ITSTV')->after('waktu_akhir');
			$table->boolean('peserta_banyak')->comment('Apakah peserta lebih dari 500')->after('relay_ITSTV');
			$table->string('api_host_nama')->nullable()->after('peserta_banyak');
			$table->string('api_host_email')->nullable()->after('api_host_nama');
		});

		DB::statement('CREATE TEMPORARY TABLE temp_bookings_table AS (SELECT 
			b.user_id, b.nama_acara, b.unit_id, b.file_pendukung, 
			bt.waktu_mulai, bt.waktu_akhir, bt.relay_ITSTV, bt.peserta_banyak, 
			host.nama as api_host_email, 
			bt.disetujui, b.deskripsi_disetujui, b.created_at, b.updated_at
			FROM bookings as b
			INNER JOIN booking_times as bt ON bt.booking_id = b.id
			LEFT JOIN host_accounts as host ON host.id = bt.host_account_id
		);');
		DB::statement('DELETE FROM bookings;');
		DB::statement('ALTER TABLE bookings AUTO_INCREMENT=1;');
		DB::statement('INSERT INTO bookings(user_id, nama_acara, unit_id, file_pendukung, 
			waktu_mulai, waktu_akhir, relay_ITSTV, peserta_banyak, 
			api_host_email, disetujui, deskripsi_disetujui, created_at, updated_at)
			SELECT user_id, nama_acara, unit_id, file_pendukung, 
			waktu_mulai, waktu_akhir, relay_ITSTV, peserta_banyak, 
			api_host_email, disetujui, deskripsi_disetujui, created_at, updated_at
			FROM temp_bookings_table;
		');
		DB::statement('DROP TABLE temp_bookings_table');

		Schema::dropIfExists('booking_times');
	}
}
