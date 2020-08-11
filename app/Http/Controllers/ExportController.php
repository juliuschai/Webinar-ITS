<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Helpers\FileHelper;
use App\Http\Requests\ExportFormRequest;
use App\Kategori;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    function view() {
        $kategoris = Kategori::get();
        return view('admin.export.form', compact('kategoris'));
    }

    function downloadBooking(ExportFormRequest $request) {
        $query = DB::table('bookings as b')
            ->join('booking_times as bt', 'bt.booking_id', '=', 'b.id')
            ->join('users as u', 'u.id', '=', 'b.user_id')
            ->join('groups as g', 'g.id', '=', 'u.group_id')
            ->join('units', 'units.id', '=', 'b.unit_id')
            ->leftJoin('kategoris as k', 'k.id', '=', 'b.kategori_id')
            ->leftJoin('host_accounts as host', 'host.id', '=', 'bt.host_account_id')
            ->orderBy('b.waktu_mulai');

        if (!$request->has('semuaWaktu')) {
            $query = $query->where('b.waktu_mulai', '>', Carbon::parse($request->waktuMulai))
                ->where('b.waktu_mulai', '<', Carbon::parse($request->waktuAkhir));
        }
        if ($request->kategori) {
            $query = $query->where('b.kategori_id', $request->kategori);
        }
        // Status filter
        if ($request->status) {
            if ($request->status === "true") {
                $query = $query->where('b.disetujui', 'true');
            } else if ($request->status === "false") {
                $query = $query->where('b.disetujui', 'false');
            } else if ($request->status === "null") {
                $query = $query->whereNull('b.disetujui');
            }
        }
        $datas = $query->get([
            'u.nama as user_nama',
            'u.email as user_email',
            'u.integra as user_integra',
            'u.no_wa as user_no_wa',
            'g.nama as user_group_nama',
            'k.nama as b_kategori',
            'b.nama_acara as b_nama_acara',
            'units.nama as b_unit_nama',
            'b.file_pendukung as b_file_pendukung',
            DB::raw('DATE_ADD(b.waktu_mulai, INTERVAL 7 HOUR) as b_waktu_mulai'),
            DB::raw('DATE_ADD(b.waktu_akhir, INTERVAL 7 HOUR) as b_waktu_akhir'),
            'b.disetujui as b_disetujui',
            'b.deskripsi_disetujui as b_deskripsi_disetujui',
            DB::raw('DATE_ADD(bt.waktu_mulai, INTERVAL 7 HOUR) as bt_waktu_mulai'),
            DB::raw('DATE_ADD(bt.waktu_akhir, INTERVAL 7 HOUR) as bt_waktu_akhir'),
            'bt.relay_ITSTV as bt_relay_ITSTV',
            'bt.gladi as bt_gladi',
            'host.nama as bt_host_nama',
            'bt.webinar_id as bt_webinar_id',
        ]);

        // Populate the rest of the datas
        foreach ($datas as $data) {
            // Nama file
            $data->b_nama_file = Booking::generateFilenameWithParam(
                $data->user_integra,
                $data->user_email,
                $data->b_unit_nama,
                $data->b_waktu_mulai,
                $data->b_file_pendukung
            );
            // disetujui
            if ($data->b_disetujui == true) {$data->b_disetujui = "Iya";}
            else if ($data->b_disetujui === false) {$data->b_disetujui = "Tidak";}
            else if ($data->b_disetujui === null) {$data->b_disetujui = "Menunggu Konfirmasi";}
            // relay_itstv
            $data->bt_relay_ITSTV=$data->bt_relay_ITSTV?'Iya':'Tidak';
            // gladi
            $data->bt_gladi=$data->bt_gladi?'Iya':'Tidak';
        }
        
        $htmlString = view('admin.export.table', compact('datas'));
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
        $spreadsheet = $reader->loadFromString($htmlString);
        
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xls');
        $currentTime = Carbon::now('Asia/Jakarta')->format("Y-m-d_Hi");
        if (!file_exists(storage_path("app/export"))) {
            mkdir(storage_path("app/export"), 0777, true);
        }
        $filename = "export_$currentTime.xls";
        $writer->save(storage_path("app/export/$filename"));
        return FileHelper::downloadDokumenOrFail("export/$filename", $filename);
        // FileHelper::deleteDokumenOrFail($filename);
    }

    function downloadFiles(ExportFormRequest $request) {
        if (!$request->has('semuaWaktu')) {
            $query = $query->where('b.waktu_mulai', '>', Carbon::parse($request->waktuMulai))
                ->where('b.waktu_mulai', '<', Carbon::parse($request->waktuAkhir));
        }
        if ($request->kategori) {
            $query = $query->where('b.kategori_id', $request->kategori);
        }
        // Status filter
        if ($request->status) {
            if ($request->status === "true") {
                $query = $query->where('b.disetujui', 'true');
            } else if ($request->status === "false") {
                $query = $query->where('b.disetujui', 'false');
            } else if ($request->status === "null") {
                $query = $query->whereNull('b.disetujui');
            }
        }
    }
}
