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
			$table->string('pass')->default("pass");
			$table->boolean('type_banyak')->comment('Apakah akun ini untuk peserta 501-1000?');
		});

		DB::table('host_accounts')->insert([
			['nama'=>'500 (1)', 'type_banyak' => false],
			['nama'=>'500 (2)', 'type_banyak' => false],
			['nama'=>'1000 (1)', 'type_banyak' => true],
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
