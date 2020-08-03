<?php

namespace App;

use App\Helpers\FileHelper;
use Illuminate\Database\Eloquent\Model;

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

	/**
	 * Generate file name for file pendukung
	 */
	function generateFilename() {
		if (isset($this->file_pendukung)) {
			$user = User::findOrFail($this->user_id);
			$unit_nama = Unit::findOrFail($this->unit_id)->nama;
			$when = date("Y-m-d_Hi", strtotime($this->waktu_mulai));
			$fileExt = pathinfo($this->file_pendukung, PATHINFO_EXTENSION);
			$fileName = $user->integra.'_'.$user->email.'_'.$unit_nama.'_'.$when.'.'.$fileExt;
			return $fileName;
		} else {
			return null;
		}
	}

	static function viewBookingList() {
		return Booking::join('units', 'units.id', '=', 'bookings.unit_id')
			->select('bookings.*', 'units.nama');
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

	function setOrgFields($id) {
		$unit = Unit::join('unit_types as t', 't.id', '=', 'units.unit_type_id')
			->where('units.id', '=', $id)
			->first(['units.nama as nama', 't.nama as type']);
		$this->unit_type = $unit->type;
		$this->unit = $unit->nama;
	}

	function abortIfVerified() {
		if ($this->disetujui != null) {
			abort(403, 'Booking yang sudah di verify tidak bisa di edit');
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

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
