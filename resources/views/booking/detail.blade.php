@extends('layouts.user')

@section('content')

<div class="right_col booking" role="main">
    <div class="col-md-12 col-sm-12">
        <div class="card detail">
            @foreach( $booking as $booking)
            <div class="card-header detail">
            <!-- {{ $booking->nama_acara }} -->
            Webinar
            </div>
            <div class="card-body detail">
                <h6 class="card-title">Nama Acara</h6>
                <p class="card-text">{{ $booking->nama_acara }}</p>

                <h6 class="card-title">Unit/Departemen</h6>
                <p class="card-text">{{ $booking->unit }}</p>

                <h6 class="card-title">Nama Anda</h6>
                <p class="card-text">{{ $booking->nama_booker }}</p>

                <h6 class="card-title">Email ITS</h6>
                <p class="card-text">{{ $booking->email_its }}</p>

                <h6 class="card-title">Tanggal Pelaksanaan Webinar</h6>
                <p class="card-text">{{ $booking->nama_acara }}</p>
                
                <h6 class="card-title">Waktu Mulai Webinar</h6>
                <p class="card-text">{{ $booking->nama_acara }}</p>
                
                <h6 class="card-title">Waktu Selesai Webinar</h6>
                <p class="card-text">{{ $booking->nama_acara }}</p>

                <h6 class="card-title">Durasi Webinar</h6>
                <p class="card-text">{{ $booking->nama_acara }}</p>

                <h6 class="card-title">Civitas Akademik</h6>
                <p class="card-text">{{ $booking->civitas }}</p>

                <h6 class="card-title">Relay ke ITS TV</h6>
                <p class="card-text">{{ $booking->nama_acara }}</p>

                <h6 class="card-title">Jumlah Peserta</h6>
                <p class="card-text">{{ $booking->nama_acara }}</p>

                <h6 class="card-title">Current Approval Status</h6>
                <p class="card-text">{{ $booking->nama_acara }}</p>

                <h6 class="card-title">Alasan</h6>
                <p class="card-text">{{ $booking->nama_acara }}</p>

                <h6 class="card-title">Host Name</h6>
                <p class="card-text">{{ $booking->nama_acara }}</p>

                <h6 class="card-title">Host Email</h6>
                <p class="card-text">{{ $booking->nama_acara }}</p>
                
                <a href="#" class="btn btn-detail">Back</a>
                
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection