<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HostAccount extends Model
{
	public $timestamps = false;

	static function getValidAccounts($start, $end) {
		return HostAccount::from('host_accounts as h')
			->whereNotExists(function($q) use ($start, $end) {
				$q->select(DB::raw(1))
					->from('booking_times as bt')
					->where('bt.host_account_id', 'h.id')
					->where('bt.waktu_akhir', '>', $start)
					->where('bt.waktu_mulai', '<', $end);
			})->get();
	}
}
