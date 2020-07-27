@extends('layouts.header')

@section('content')

	<div class="right_col booking" role="main">
		<div class="col-md-12 col-sm-12">
            <h2 class="table-title">Daftar Webinar</h2>
            <table id="sortData" class="table table-bordered table-striped table-bordered table-hover">
                <thead class="thead-custom-blue">
                    <tr>
                    <th class="text-center" scope="col">#</th>
                    <th class="text-center" scope="col">Tanggal Booking</th>
                    <th class="text-center" scope="col">Tanggal Webinar</th>
                    <th class="text-center" scope="col">Waktu</th>
                    <th class="text-center" scope="col">Nama Acara</th>
                    <th class="text-center" scope="col">Penyelenggara Acara</th>
                    <th class="text-center" scope="col">Status</th>
                    <th class="text-center" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $bookings as $booking)
                    <tr>
                    <th class="text-center px-0" scope="row">{{ $loop->iteration+(($bookings->currentPage()-1)*10) }}</th>
                    <td class="text-center px-0">{{ date('d-m-Y', strtotime($booking->created_at)) }}</td>
                    <td class="text-center px-0">{{ date('d-m-Y', strtotime($booking->waktu_mulai)) }}</td>
                    <td class="text-center px-0">{{ date('H:i:s', strtotime($booking->waktu_mulai)) }}</td>
                    <td class="text-center px-0" style="width: 30%">{{ $booking->nama_acara }}</td>
                    <td class="text-center px-0">{{ $booking->nama }}</td>
                    @if ($booking->disetujui == '0') 
                    <td style="background-color: #FF6961; padding: 8px 8px;" class="text-center">Ditolak</td>
                    @elseif ($booking->disetujui == '1') 
                    <td style="background-color: #99d18f; padding: 8px 8px;" class="text-center">Disetujui</td>
                    @else
                    <td style="background-color: #FCF0CF; padding: 8px 8px;" class="text-center">Menunggu</br>Konfirmasi</td>
                    @endif
                    <td class="text-center" style="width: 13%; padding: 5px 8px;">
                    @if ($booking->disetujui == '1' || $booking->disetujui == '0')
                        <a href="{{ url('/booking/view/'.$booking->id) }}">
                        <button style="padding: 3px 8px" type="button" class="wrapper btn btn-custom-primary" title="Detail Webinar">
                            <i class="fa fa-search"></i>
                        </button>
                        </a>
                        <a href="{{ url('/booking/edit/'.$booking->id) }}">
                        <button disabled style="padding: 3px 8px" type="button" class="wrapper btn btn-custom-warning" title="Edit Webinar">
                            <i class="fa fa-pencil"></i>
                        </button>
                        </a> 
                        <form action="{{ url('/booking/delete/'.$booking->id) }}" method="post" class="d-inline">    
                        @method('delete')
                        @csrf
                        <button disabled style="padding: 3px 8px" type="submit" class="wrapper btn btn-custom-danger" onclick="return confirm('Apakah anda yakin untuk menghapus Webinar {{$booking->nama_acara}} ?')" title="Hapus Webinar">
                            <i class="fa fa-trash-o"></i>
                        </button>
                        </form>
                    @elseif ($booking->disetujui == NULL)
                        <a href="{{ url('/booking/view/'.$booking->id) }}">
                        <button style="padding: 3px 8px" type="button" class="wrapper btn btn-custom-primary" title="Detail Webinar">
                            <i class="fa fa-search"></i>
                        </button>
                        </a>
                        <a href="{{ url('/booking/edit/'.$booking->id) }}">
                        <button style="padding: 3px 8px" type="button" class="wrapper btn btn-custom-warning" title="Edit Webinar">
                            <i class="fa fa-pencil"></i>
                        </button>
                        </a> 
                        <form action="{{ url('/booking/delete/'.$booking->id) }}" method="post" class="d-inline">    
                        @method('delete')
                        @csrf
                        <button style="padding: 3px 8px;" type="submit" class="wrapper btn btn-custom-danger" onclick="return confirm('Apakah anda yakin untuk menghapus Webinar {{$booking->nama_acara}} ?')" title="Hapus Webinar">
                            <i class="fa fa-trash-o"></i>
                        </button>
                        </form>
                    @endif
                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
		</div>
	</div>
<!-- </div> -->
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

{{ $bookings->links() }}

@endsection