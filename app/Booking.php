<?php

namespace App;

use App\Helpers\FileHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Booking extends Model
{
	protected $casts = [
		'waktu_mulai' => 'datetime',
		'waktu_akhir' => 'datetime',
	];

	function setUserId($user_id) {
		$this->user_id = $user_id;
	}

	/**
	 * Parses request based on booking.form view (SaveBookingRequest)
	 * to a booking model instance. Also needs owner user_id
	 * @* @param Request request request data from booking.form view (SaveBookingRequest)
	 * @* @param int user_id id of booking owner (currently logged in user)
	 */
	function saveFromRequest($request) {
		// True if checkbox checked, false not checked
		$this->kategori_id = $request->kategoriAcara;
		$this->nama_acara = $request->namaAcara;
		$this->unit_id = $request->penyelengaraAcara;
		$this->disetujui = null;
		// If there's a new file being uploaded
		if ($request->has('dokumenPendukung')) {
			// If current booking already has a file pendukung
			if (isset($this->file_pendukung)) {
				// Delete old file, save new file
				FileHelper::deleteDokumenOrFail($this->file_pendukung);
			}
			$this->saveFileFromRequest($request);
		}
		$this->save();
		$times = BookingTime::saveFromRequest($request, $this->id);
		$this->waktu_mulai = $times['waktu_mulai'];
		$this->waktu_akhir = $times['waktu_akhir'];
		$this->disetujui = null;
		$this->save();
	}

	function saveFileFromRequest($request) {
		$file = $request->file('dokumenPendukung');
		$this->file_pendukung = $file->store('dokumen', 'local');
	}

	static function generateFilenameWithParam($user_integra, $user_email, $unit_nama, $when, $file_in_db) {
		$when = Carbon::parse($when)->format('Y-m-d_Hi');
		$fileExt = pathinfo($file_in_db, PATHINFO_EXTENSION);
		$fileName = $user_integra.'_'.$user_email.'_'.$unit_nama.'_'.$when.'.'.$fileExt;
		return $fileName;
	}
	/**
	 * Generate file name for file pendukung
	 */
	function generateFilename() {
		if (isset($this->file_pendukung)) {
			$user = User::findOrFail($this->user_id);
			$unit_nama = Unit::findOrFail($this->unit_id)->nama;
			$when = Carbon::parse($this->waktu_mulai)->setTimezone('Asia/Jakarta')->format('Y-m-d_Hi');
			$fileExt = pathinfo($this->file_pendukung, PATHINFO_EXTENSION);
			$fileName = $user->integra.'_'.$user->email.'_'.$unit_nama.'_'.$when.'.'.$fileExt;
			return $fileName;
		} else {
			return null;
		}
	}

	static function viewBookingList($user_id_to_filter = "")
	{
		// Untuk siapapun yang akan maintain ini, saya minta maaf lahir dan batin.
		// Habis lu maintain ini project lu masuk surga gara2 doa terus
		$sub = Booking::join('units', 'units.id', '=', 'bookings.unit_id')
		->join('booking_times as bt', 'booking_id', '=', 'bookings.id')
		->leftJoin('host_accounts as h', 'h.id', '=', 'bt.host_account_id')
		->leftJoin('users as adm', 'adm.id', '=', 'bookings.admin_id')
		->orderBy('bt.waktu_mulai')
		->select(
			'bookings.id',
			'bookings.created_at',
			'bookings.waktu_mulai',
			'bookings.nama_acara',
			'units.nama',
			'adm.nama as admin_dptsi_nama',
			'adm.no_wa as admin_dptsi_no_wa',
			// Coalesce: replace null with empty string
			DB::raw('CONCAT(DATE_FORMAT(bt.waktu_mulai, "%Y-%m-%d\T%TZ"), " - ", COALESCE(`h`.`nama`, "Belum dipilih")) as `book_times`'),
			'bookings.disetujui as disetujui'
		);
		if ($user_id_to_filter) {
			$sub = $sub->where('bookings.user_id', $user_id_to_filter);
		}

		return Booking::from(DB::raw("({$sub->toSql()}) as sub"))
		->mergeBindings($sub->getQuery()) // you need to get underlying Query Builder
			->groupBy(
				'id')
			->select(
				'id',
				'created_at',
				'waktu_mulai',
				'nama_acara',
				'nama',
				'admin_dptsi_nama',
				'admin_dptsi_no_wa',
				DB::raw('GROUP_CONCAT(`book_times`) as `book_times_summary`'),
				'disetujui'
			);
	}

	function verifyBooking($requestVerifies) {
		$book_times = BookingTime::
			where('booking_id', $this->id)
			->get();

		foreach ($book_times as $book_time) {
			$plucked[$book_time->id] = $book_time;
		}

		foreach ($requestVerifies as $reqVerify) {
			$id = $reqVerify['id'];
			if (isset($plucked[$id])) {
				$book_time = $plucked[$id];
				$book_time->verifyBookTime($reqVerify);
			}
		}
	}

	function checkApproved() {
		$book_times = BookingTime::
			where('booking_id', $this->id)
			->get();
		foreach ($book_times as $book_time) {
			if (!$book_time->disetujui) {
				return false;
			}
		}

		return true;
	}

	function getTimes() {
		$book_times = BookingTime::where('booking_id', '=', $this->id)
			->orderBy('gladi', 'DESC')
			->orderBy('waktu_mulai')
			->get();
		foreach ($book_times as $book_time) {
			$book_time->setHostAccount();
		}
		return $book_times;
	}

	function setUserFields($id) {
		$user = User::findOrFail($id);
		$this->integra_pic = $user->integra;
		$this->nama_pic = $user->nama;
		$this->email_pic = $user->email;
		$this->no_wa = $user->no_wa;
		$this->sivitas = Group::getNamaFromId($user->group_id);
	}

	function setAdminFields($id) {
		$admin = User::find($id);
		if ($admin) {
			$this->admin_dptsi_nama = $admin->nama;
			$this->admin_dptsi_no_wa = $admin->no_wa;
		}
	}

	function setOrgFields($id) {
		$unit = Unit::join('unit_types as t', 't.id', '=', 'units.unit_type_id')
			->where('units.id', '=', $id)
			->first(['units.nama as nama', 't.nama as type']);
		$this->unit_type = $unit->type;
		$this->unit = $unit->nama;
	}

	function abortIfVerified() {
		// If user is not admin
		if (!User::findOrLogout(auth()->id())->isAdmin()) {
			// And booking is disetujui
			if ($this->disetujui != null) {
				// Abort Disallow user to edit
				abort(403, 'Booking yang sudah di verify tidak bisa di edit');
			}
		}
	}

	/**
	 * Aborts the current request if the booking is not
	 * owned by the current user
	 * @return void
	 */
	function abortButOwner($id) {
		if (!$this->isOwner($id)) {
			abort(403, 'Anda bukan pemilik dari booking ini');
		}
	}

	/**
	 * Check if user is owner of booking
	 * @return boolean
	 */
	function isOwner($id) {
		return $this->user_id == $id;
	}

	function user() {
		return $this->hasOne('App\User', 'id', 'user_id');
	}

	function kategori() {
			return $this->hasOne('App\Kategori', 'id', 'kategori_id');
	}

    function book_times()
    {
        return $this->hasMany('App\BookingTime', 'booking_id', 'id');
    }

}
