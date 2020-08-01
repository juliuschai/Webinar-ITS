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
		});

		DB::table('host_accounts')->insert([
			['nama'=>'500 (1)'],
			['nama'=>'500 (2)'],
			['nama'=>'1000 (1)'],
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
