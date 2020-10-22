<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HostAccount extends Model
{
	public $timestamps = false;

	static function getValidAccounts($start, $end, $book_id, $banyak, $tipe_zoom) {
		$start = Carbon::parse($start)->subHour();
		$end = Carbon::parse($end);

        $bindings = [
            'tipe_zoom' => $tipe_zoom,
            'banyak' => $banyak,
			'start' => $start,
			'end' => $end,
			'book_id' => $book_id,
		];
		$accounts = DB::select('select *
			from `host_accounts` as `h`
            where h.tipe_zoom = :tipe_zoom
            and h.max_peserta >= :banyak
            and not exists (
				select 1 from `booking_times` as `bt`
				where `bt`.`waktu_akhir` > :start
				and `bt`.`waktu_mulai` < :end
				and `bt`.`host_account_id` = h.id
				and not bt.id = :book_id)', $bindings);
		return $accounts;
	}

	static function checkAvailability($start, $end, $book_id, $banyak, $id, $tipe_zoom) {
		$start = Carbon::parse($start)->subHour();
		$end = Carbon::parse($end);

		$bindings = [
            'tipe_zoom' => $tipe_zoom,
            'banyak' => $banyak,
			'id' => $id,
			'start' => $start,
			'end' => $end,
			'book_id' => $book_id,
		];
		// $count = DB::select('select COUNT(*) as amount
		$count = DB::select('select COUNT(*) as amount
			from `host_accounts` as `h`
            where h.id = :id
            and h.tipe_zoom = :tipe_zoom
            and h.max_peserta >= :banyak
			and not exists (
				select 1 from `booking_times` as `bt`
				where `bt`.`waktu_akhir` > :start
				and `bt`.`waktu_mulai` < :end
				and `bt`.`host_account_id` = h.id
				and not bt.id = :book_id)', $bindings);

		if ($count[0]->amount == 1) {
			return true;
		} else {
			return false;
		}
	}

	static function viewHostList() {
		return HostAccount::get();
	}
}
