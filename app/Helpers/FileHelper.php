<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class FileHelper {
	static function getDokumenOrFail($filename) {
		if (Storage::disk('local')->exists($filename)){
			return Storage::disk('local')->response($filename);
		} else {
			abort(404, 'File tidak ditemukan di server');
		}
	}

	static function downloadDokumenOrFail($filename, $userFilename) {
		if (Storage::disk('local')->exists($filename)){
			return Storage::disk('local')->download($filename, $userFilename);
		} else {
			abort(404, 'File tidak ditemukan di server');
		}
	}

	static function deleteDokumenOrFail($filename) {
		if (Storage::disk('local')->exists($filename)){
			return Storage::disk('local')->delete($filename);
		} else {
			abort(404, 'File tidak ditemukan di server');
		}
	}

	static function renameDokumenOrFail($from, $to) {
			
	}
}
