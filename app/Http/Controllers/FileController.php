<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\FileHelper;

class FileController extends Controller
{
    function getDokumen(Request $request) {
        // $request->booking is defined in the middleware
        $booking = $request->booking;
        $filename = $booking->file_pendukung;
        return FileHelper::getDokumenOrFail($filename);
    }

    function downloadDokumen(Request $request) {
        // $request->booking is defined in the middleware
        $booking = $request->booking;
        $filename = $booking->file_pendukung;
        $userFilename = $booking->generateFilename();
        return FileHelper::downloadDokumenOrFail($filename, $userFilename);
    }

    function deleteDokumen(Request $request) {
        // $request->booking is defined in the middleware
        $booking = $request->booking;
        $filename = $booking->file_pendukung;
        return FileHelper::deleteDokumenOrFail($filename);
    }

    function renameDokumen() {
        // TODO:
    }
}
