<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HostAccount extends Model
{
	public $timestamps = false;

	static function getValidAccounts($start, $end, $book_id, $banyak) {
		$start = Carbon::createFromTimestamp(strtotime($start))->subHour();
		$end = Carbon::createFromTimestamp(strtotime($end));
		// Siapapun yang maintain ini, saya turut berduka cita.
		// When all hope seems lost: "php artisan inspire"
		if ($banyak) {
			$typeQuery = 'h.type_banyak = true and ';
		} else {
			$typeQuery = '';
		}
		$accounts = DB::select('select * 
			from `host_accounts` as `h` 
			where '.$typeQuery.'
			not exists (
				select 1 from `booking_times` as `bt` 
				where `bt`.`waktu_akhir` > "'.$start.'" 
				and `bt`.`waktu_mulai` < "'.$end.'"
				and `bt`.`host_account_id` = h.id 
				and not bt.id = '.$book_id.')');
		return $accounts;
	}

	static function checkAvailability($start, $end, $book_id, $banyak, $id) {
		$start = Carbon::createFromTimestamp(strtotime($start))->subHour();
		$end = Carbon::createFromTimestamp(strtotime($end));
		if ($banyak) {
			$typeQuery = 'h.type_banyak = true and ';
		} else {
			$typeQuery = '';
		}
		$count = DB::select('select COUNT(*) 
			from `host_accounts` as `h`
			where '.$typeQuery.'
			h.id = '.$id.'
			and not exists (
				select 1 from `booking_times` as `bt` 
				where `bt`.`waktu_akhir` > "'.$start.'" 
				and `bt`.`waktu_mulai` < "'.$end.'"
				and `bt`.`host_account_id` = h.id 
				and not bt.id = '.$book_id.')');
		$idx = "COUNT(*)";
		if ($count[0]->$idx == 1) {
			return true;
		} else {
			return false;
		}
	}
}
