<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Unit;
use App\UnitType;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChartController extends Controller
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function index()
    {
        $bookings = Booking::select(\DB::raw("COUNT(*) as count"))
                    ->whereYear('created_at', date('Y'))
                    ->groupBy(\DB::raw("Month(created_at)"))
                    ->pluck('count');

        $nama_booking = Booking::select(\DB::raw("COUNT(*) as count, Month(created_at) as month"))
                    ->whereYear('created_at', date('Y'))
                    ->groupBy(\DB::raw("Month(created_at)"))
                    ->pluck('month');

        // unit
        $departements = Booking::select(\DB::raw("COUNT(*) as count"))
                    ->join('units', 'units.id', '=', 'bookings.unit_id')
                    ->where('units.unit_type_id', '=', '1')
                    ->groupBy(\DB::raw('units.nama'))
                    ->orderByRaw('count DESC')
                    ->limit(5)
                    ->pluck('count');

        $nama_departemen = Booking::select(\DB::raw("COUNT(*) as count, units.nama"))
                    ->join('units', 'units.id', '=', 'bookings.unit_id')
                    ->where('units.unit_type_id', '=', '1')
                    ->groupBy(\DB::raw('units.nama'))
                    ->orderByRaw('count DESC')
                    ->limit(5)
                    ->pluck('units.nama');
        
        $faculties = Booking::select(\DB::raw("COUNT(*) as count"))
                    ->join('units', 'units.id', '=', 'bookings.unit_id')
                    ->where('units.unit_type_id', '=', '2')
                    ->groupBy(\DB::raw('units.nama'))
                    // ->orderByRaw('count DESC')
                    ->orderByRaw('units.nama DESC')
                    ->pluck('count');

        // dd($faculties);

        // $nama_fakultas = Booking::select(\DB::raw("COUNT(*) as count, units.nama"))
        //             ->join('units', 'units.id', '=', 'bookings.unit_id')
        //             ->where('units.unit_type_id', '=', '2')
        //             ->groupBy(\DB::raw('units.nama'))
        //             ->orderByRaw('count DESC')
        //             ->pluck('units.nama');

        $nama_fakultas = Unit::select(\DB::raw("units.nama"))
                    ->where('units.unit_type_id', '=', '2')
                    ->orderByRaw('units.nama DESC')
                    ->pluck('units.nama');

        // dd($nama_fakultas);

        $units = Booking::select(\DB::raw("COUNT(*) as count"))
                    ->join('units', 'units.id', '=', 'bookings.unit_id')
                    ->where('units.unit_type_id', '=', '3')
                    ->groupBy(\DB::raw('units.nama'))
                    ->orderByRaw('count DESC')
                    ->limit(5)
                    ->pluck('count');

        $nama_unit = Booking::select(\DB::raw("COUNT(*) as count, units.nama"))
                    ->join('units', 'units.id', '=', 'bookings.unit_id')
                    ->where('units.unit_type_id', '=', '3')
                    ->groupBy(\DB::raw('units.nama'))
                    ->orderByRaw('count DESC')
                    ->limit(5)
                    ->pluck('units.nama');
        
        // sivitas
        $dosen = Booking::select(\DB::raw("COUNT(*) as count"))
                    ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->join('groups', 'groups.id', '=', 'users.group_id')
                    ->where('groups.id', '=', '1')
                    ->orWhere('groups.id', '=', '4')
                    ->groupBy(\DB::raw('groups.nama'))
                    ->pluck('count');

        $tendik = Booking::select(\DB::raw("COUNT(*) as count"))
                    ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->join('groups', 'groups.id', '=', 'users.group_id')
                    ->where('groups.id', '=', '2')
                    ->orWhere('groups.id', '=', '5')
                    ->groupBy(\DB::raw('groups.nama'))
                    ->pluck('count');

        $mahasiswa = Booking::select(\DB::raw("COUNT(*) as count"))
                    ->join('users', 'users.id', '=', 'bookings.user_id')
                    ->join('groups', 'groups.id', '=', 'users.group_id')
                    ->where('groups.id', '=', '3')
                    ->groupBy(\DB::raw('groups.nama'))
                    ->pluck('count');

        //meeting 
        $test = Booking::select(\DB::raw("CONVERT(SUM(time_to_sec(timediff(bookings.waktu_akhir, bookings.waktu_mulai)) / 60), SIGNED) as waktu"))
                    ->join('units', 'units.id', '=', 'bookings.unit_id')
                    ->groupBy(\DB::raw('units.nama'))
                    ->orderByRaw('waktu DESC')
                    ->limit(5)
                    ->pluck('waktu');

        $nama_test = Booking::select(\DB::raw("CONVERT(SUM(time_to_sec(timediff(bookings.waktu_akhir, bookings.waktu_mulai)) / 60), SIGNED) as waktu, units.nama"))
                    ->join('units', 'units.id', '=', 'bookings.unit_id')
                    ->groupBy(\DB::raw('units.nama'))
                    ->orderByRaw('waktu DESC')
                    ->limit(5)
                    ->pluck('units.nama');

        return view('chart.chart', compact('bookings', 'departements', 'faculties', 'units', 
                'dosen', 'tendik', 'mahasiswa', 'test', 'nama_test', 'nama_booking'
                ,'nama_departemen', 'nama_fakultas', 'nama_unit'));

    }
    
}