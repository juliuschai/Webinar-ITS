@extends('layouts.header')

@section('content')

	<div class="right_col booking" role="main">
		<div class="col-md-12 col-sm-12">
            <h2 class="table-title">List {{$tipe_zoom}}</h2>
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
                    <td class="text-center">{{ $booking->waktu_mulai->format(DateTime::ATOM) }}</td>
                    <td class="text-center">{{ $booking->waktu_mulai->format(DateTime::ATOM) }}</td>
                    <td class="text-center">{{ $booking->nama_acara }}</td>
                    <td class="text-center">
                        <a href="{{ url('/booking/detail/'.$booking->id) }}">
                        <button type="button" class="btn btn-custom-primary" title="Detail {{$tipe_zoom}}">
                            <i class="fa fa-search">  Detail</i>
                        </button>
                        </a>
                    </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
		</div>
	</div>
<!-- </div> -->
@endsection
