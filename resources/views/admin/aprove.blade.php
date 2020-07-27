@extends('layouts.header')

@section('content')

    @if($isAdmin)
	<div class="right_col booking" role="main">
		<div class="col-md-12 col-sm-12">
            <h2 class="table-title">Webinar</h2>
            <table class="table table-bordered table-striped table-bordered table-hover">
                <thead class="thead-custom-blue">
                    <tr>
                    <th class="text-center" scope="col">#</th>
                    <th class="text-center" scope="col">Tanggal Booking</th>
                    <th class="text-center" scope="col">Tanggal Webinar</th>
                    <th class="text-center" scope="col">Waktu</th>
                    <th class="text-center" scope="col">Nama Acara</th>
                    <th class="text-center" scope="col">Penyelenggara Acara</th>
                    <th class="text-center" scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $bookings as $booking)
                    <tr>
                    <th class="text-center" scope="row">{{ $loop->iteration+(($bookings->currentPage()-1)*10) }}</th>
                    <td class="text-center px-0">{{ date('d-m-Y', strtotime($booking->created_at)) }}</td>
                    <td class="text-center px-0">{{ date('d-m-Y', strtotime($booking->waktu_mulai)) }}</td>
                    <td class="text-center px-0">{{ date('H:i:s', strtotime($booking->waktu_mulai)) }}</td>
                    <td class="text-center px-0">{{ $booking->nama_acara }}</td>
                    <td class="text-center px-0">{{ $booking->nama }}</td>
                    @if ($booking->disetujui == '0') 
                    <td style="background-color: #FF6961; padding: 8px 8px;" class="text-center">Ditolak</td>
                    @elseif ($booking->disetujui == '1') 
                    <td style="background-color: #99d18f; padding: 8px 8px;" class="text-center">Disetujui</td>
                    @else
                    <td style="background-color: #FCF0CF; padding: 8px 8px;" class="text-center">Menunggu</br>Konfirmasi</td>
                    @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
		</div>
	</div>
<!-- </div> -->
    @endif

{{ $bookings->links() }}
@endsection
