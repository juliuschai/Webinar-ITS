@extends('layouts.user')

@section('content')

	<div class="right_col booking" role="main">
		<div class="col-md-12 col-sm-12">
            <h2 class="table-title">Waiting List Webinar</h2>
            <table class="table table-bordered table-striped table-bordered table-hover">
                <thead class="thead-custom-blue">
                    <tr>
                    <th class="text-center" scope="col">#</th>
                    <th class="text-center" scope="col">Tanggal</th>
                    <th class="text-center" scope="col">Waktu</th>
                    <th class="text-center" scope="col">Nama Acara</th>
                    <th class="text-center" scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach( $booking as $booking)
                    <tr>
                    <th class="text-center" scope="row">{{ $loop->iteration }}</th>
                    <td class="text-center">{{ date('d-m-Y', strtotime($booking->waktu_mulai)) }}</td>
                    <td class="text-center">{{ date('H:i:s', strtotime($booking->waktu_mulai)) }}</td>
                    <td class="text-center">{{ $booking->nama_acara }}</td>
                    <td class="text-center">
                        <a href="{{ url('/booking/view/'.$booking->id) }}">
                        <button type="button" class="btn btn-custom-primary" title="Detail Webinar">
                            <i class="fa fa-search"></i>
                        </button>
                        </a>
                        <a href="{{ url('/booking/edit/'.$booking->id) }}">
                        <button type="button" class="btn btn-custom-warning" title="Edit Webinar">
                            <i class="fa fa-pencil"></i>
                        </button>
                        </a>
                        <form action="{{ url('/booking/delete/'.$booking->id) }}" method="post" class="d-inline">    
                        @method('delete')
                        @csrf
                        <button type="submit" class="btn btn-custom-danger" onclick="return confirm('Apakah anda yakin untuk menghapus Webinar {{$booking->nama_acara}} ?')" title="Hapus Webinar">
                            <i class="fa fa-trash-o"></i>
                        </button>
                        </form>
                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
		</div>
	</div>
<!-- </div> -->
<script defer>

	function getTimeZoneOffsetInMs() {
		return new Date().getTimezoneOffset() * -60 * 1000;
	}
	
	function onupdateDurasi() {
		let start = new Date(document.getElementById('waktuMulai').value);
		let hours = document.getElementById('durasi').value;

		let end = new Date(start.getTime() + getTimeZoneOffsetInMs() + parseFloat(hours)*3600*1000);
		document.getElementById('waktuSelesai').value = end.toISOString().substring(0, 16);
	}

	function onupdateWaktu() {
		let start = new Date(document.getElementById('waktuMulai').value);
		let end = new Date(document.getElementById('waktuSelesai').value);

		document.getElementById('durasi').value = (end-start)/3600/1000;
	}
	
	// Even though the script is already at the bottom of the page and inputs are already loaded,
	// Seems like the function still needs to wait until document is ready
	// idk what's not ready when the script is loaded with the html tho
	setTimeout(onupdateWaktu, 500);
</script>
@endsection
