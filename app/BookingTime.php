<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingTime extends Model
{

	protected $casts = [
		'waktu_mulai' => 'datetime',
		'waktu_akhir' => 'datetime',
		'gladi' => 'boolean',
		'relayITSTV' => 'boolean',
	];

	static function saveFromRequest($request, $bookingId) {
		$inDb = BookingTime::where('booking_id', '=', $bookingId)
			->pluck('disetujui','id');
		$retTimes = [];

		$length = count($request->bookingTimes);
		for ($i=0; $i < $length; $i++) { 
			$inBookTime = $request->bookingTimes[$i];
			// if id isn't in db, create new
			if ($inBookTime['id'] && isset($inDb[$inBookTime['id']])) {
				// if id is disetujui in db, drop changes
				if ($inDb[$inBookTime['id']] == true) {
					continue;
				} else {
					$curBookTime = BookingTime::find($inBookTime['id']);
				}
			} else {
				$curBookTime = new BookingTime();
			}
			if (!isset($retTimes['waktu_mulai'])) {
				$retTimes['waktu_mulai'] = $inBookTime['waktuMulai'];
			}
			// get the latest waktu_akhir
			$retTimes['waktu_akhir'] = $inBookTime['waktuSelesai'];

			$relayITSTV = $inBookTime['relayITSTV'] == "true" ? true:false; 
			$peserta_banyak = $inBookTime['pesertaBanyak'] == 500 ? false:true;
			$gladi = $inBookTime['gladi'] == "true" ? true:false;

			$curBookTime->booking_id = $bookingId;
			$curBookTime->waktu_mulai = $inBookTime['waktuMulai'];
			$curBookTime->waktu_akhir = $inBookTime['waktuSelesai'];
			$curBookTime->relay_ITSTV = $relayITSTV;
			$curBookTime->peserta_banyak = $peserta_banyak;
			$curBookTime->gladi = $gladi;
			$curBookTime->save();
		} 
		if (!isset($retTimes['waktu_mulai'])) {
			abort(403, 'Tidak ada sesi booking yang valid! 
				Silahkan mengecheck kembali sesi-sesi booking anda.');
		}

		return $retTimes;
	}
}
