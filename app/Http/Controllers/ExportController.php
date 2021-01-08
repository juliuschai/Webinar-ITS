<?php

namespace App\Http\Controllers;

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
            DB::raw('DATE_ADD(bt.waktu_mulai, INTERVAL 7 HOUR) as bt_waktu_mulai'),
            'u.nama as user_nama',
            'g.nama as user_group_nama',
            'b.nama_acara as b_nama_acara',
            'units.nama as b_unit_nama',
            'bt.tipe_zoom as bt_tipe_zoom',
            DB::raw('IF(bt.gladi, "gladi", "") as bt_gladi'),
            'bt.max_peserta as bt_max_peserta',
            'b.disetujui as b_disetujui',
        ]);

        // Process datas
        foreach ($datas as $data) {
            // process disetujui
            if ($data->b_disetujui == true) {$data->b_disetujui = "Iya";}
            else if ($data->b_disetujui === false) {$data->b_disetujui = "Tidak";}
            else if ($data->b_disetujui === null) {$data->b_disetujui = "Menunggu Konfirmasi";}
        }

        // Prepare csv
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        // Write to user buffer
        $callback = function() use ($datas)
        {
            $columns = [
                'Tanggal Pelaksanaan',
                'Nama PIC',
                'Group PIC',
                'Nama Acara',
                'Unit',
                'Webinar/Meeting',
                'Gladi',
                'Kapasitas (500/1000)',
                'Status'
            ];

            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach($datas as $data) {
                // The weird ass syntax is to escape commas and quotes in a csv file
                fputcsv($file, [
                    $data->bt_waktu_mulai,
                    $data->user_nama,
                    $data->user_group_nama,
                    $data->b_nama_acara,
                    $data->b_unit_nama,
                    $data->bt_tipe_zoom,
                    $data->bt_gladi,
                    $data->bt_max_peserta,
                    $data->b_disetujui,
                ]);
            }
            fclose($file);
        };

        $currentTime = Carbon::now('Asia/Jakarta')->format("Y-m-d_Hi");
        $filename = "export_$currentTime.csv";

        // return Response::stream($callback, 200, $headers);
        return response()->streamDownload($callback, $filename, $headers);

    }
}
