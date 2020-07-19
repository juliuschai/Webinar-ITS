@extends('layouts.user')

@section('content')

	<div class="right_col booking" role="main">
		<div class="col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">{{ __('Booking Form') }}</div>

				<div class="card-body">
					@if ($errors->any())
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
					@endif

					@if($isOwner || $isAdmin)
					<div class="form-group row">
						<label for="namaPic" class="col-md-4 col-form-label text-md-left">{{ __('Nama PIC') }}</label>
						<i tyle="padding-left: 1px" class="fa fa-user booking"></i>
						<label for="namaPic" class="col-md-4 col-form-label text-md-left">{{ __('Nama PIC Zoom') }}</label>
						<i class="fa fa-sticky-note-o"></i>
						<div class="col-md-6">
							<input 
								id="namaPic" type="text" class="form-control" 
								value="{{ $booking['nama_pic'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="integraPic" class="col-md-4 col-form-label text-md-left">{{ __('User Integra PIC') }}</label>
						<i class="fa fa-address-card booking"></i>
						<label for="integraPic" class="col-md-4 col-form-label text-md-left">{{ __('User Integra PIC Zoom') }}</label>
						<i class="fa fa-sticky-note-o"></i>
						<div class="col-md-6">
							<input 
								id="integraPic" type="text" class="form-control" 
								value="{{ $booking['integra_pic'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="emailPic" class="col-md-4 col-form-label text-md-left">{{ __('Email ITS PIC') }}</label>
						<i class="fa fa-envelope booking"></i>
						<label for="emailPic" class="col-md-4 col-form-label text-md-left">{{ __('Email ITS PIC Zoom') }}</label>
						<i class="fa fa-sticky-note-o"></i>
						<div class="col-md-6">
							<input 
								id="emailPic" type="text" class="form-control" 
								value="{{ $booking['email_pic'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="sivitas" class="col-md-4 col-form-label text-md-left">{{ __('Sivitas Akademika') }}</label>
						<i class="fa fa-users booking"></i>
						<div class="col-md-6">
							<input 
								id="sivitas" type="text" class="form-control" 
								value="{{ $booking['sivitas'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="penyelengaraAcara" class="col-md-4 col-form-label text-md-left">{{ __('Penyelengara Acara') }}</label>
						<i class="fa fa-user-circle booking"></i>
						<div class="col-md-6">
							<input 
								id="penyelengaraAcaraType" type="text" class="form-control" 
								value="{{ $booking['unit_type'] }}" disabled
							>
							</br>
							<input 
								id="penyelengaraAcara" type="text" class="form-control" 
								value="{{ $booking['unit_nama'] }}" disabled
							>
						</div>
					</div>	

					<div class="form-group row">
						<label for="noWa" class="col-md-4 col-form-label text-md-left">{{ __('No. WA') }}</label>
						<i class="fa fa-mobile fa-2x booking""></i>
						<div class="col-md-6">
							<input 
								id="noWa" type="tel" class="form-control"
								value="{{ $booking['no_wa'] }}" disabled
							>
						</div>
					</div>
					@endif

					<div class="form-group row">
						<label for="namaAcara" class="col-md-4 col-form-label text-md-left">{{ __('Nama Acara') }}</label>
						<i class="fa fa-sticky-note-o booking"></i>
						<div class="col-md-6">
							<input 
								id="namaAcara" type="text" class="form-control" 
								value="{{ $booking['nama_acara'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="unitDepartemen" class="col-md-4 col-form-label text-md-left">{{ __('Unit/Departemen') }}</label>
						<i class="fa fa-building booking"></i>
						<label for="penyelengaraAcara" class="col-md-4 col-form-label text-md-left">{{ __('Penyelengara Acara') }}</label>
						<i class="fa fa-sticky-note-o"></i>
						<div class="col-md-6">
							<input 
								id="penyelengaraAcaraType" type="text" class="form-control" 
								value="{{ $booking['unit_type'] }}" disabled
							>
							<input 
								id="penyelengaraAcara" type="text" class="form-control" 
								value="{{ $booking['unit'] }}" disabled
							>
						</div>
					</div>	

					<div class="form-group row">
						<label for="waktuMulai" class="col-md-4 col-form-label text-md-left">{{ __('Waktu Mulai Webinar') }}</label>
						<i class="fa fa-calendar-o booking"></i>
						<div class="col-md-6">
							<input 
								id="waktuMulai" type="datetime-local" class="form-control" 
								value="{{ $booking['waktu_mulai'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="waktuSelesai" class="col-md-4 col-form-label text-md-left">{{ __('Waktu Selesai Webinar') }}</label>
						<i class="fa fa-calendar booking"></i>
						<div class="col-md-6">
							<input 
								id="waktuSelesai" type="datetime-local" class="form-control" 
								value="{{ $booking['waktu_akhir'] }}" onchange="onupdateWaktu()" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="durasi" class="col-md-4 col-form-label text-md-left">{{ __('Durasi Webinar') }}</label>
						<i class="fa fa-clock-o booking"></i>
						<div class="col-md-6">
							<input id="durasi" type="text" class="form-control" value="" onchange="onupdateDurasi()" disabled> <div>jam</div>
						</div>
					</div>

					@if($isOwner || $isAdmin)
					<div class="form-group row">
						<label for="namaAnda" class="col-md-4 col-form-label text-md-left">{{ __('Nama Anda') }}</label>
						<i style="padding-left: 1px" class="fa fa-user booking"></i>
						<!-- style="margin-left: 1px" -->
						<div class="col-md-6">
							<input 
								id="namaAnda" type="text" class="form-control" 
								value="{{ $booking['reg_nama'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="emailITS" class="col-md-4 col-form-label text-md-left">{{ __('Email ITS') }}</label>
						<i class="fa fa-envelope-o booking"></i>
						<div class="col-md-6">
							<input 
								id="emailITS" type="email" class="form-control" 
								value="{{ $booking['reg_email'] }}" disabled autocomplete="email"
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="userIntegra" class="col-md-4 col-form-label text-md-left">{{ __('User Integra') }}</label>
						<i class="fa fa-address-card booking"></i>
						<div class="col-md-6">
							<input 
								id="userIntegra" type="text" class="form-control" 
								value="{{ $booking['reg_integra'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="group" class="col-md-4 col-form-label text-md-left">{{ __('Group') }}</label>
						<i class="fa fa-users booking"></i>
						<div class="col-md-6">
							<input 
								id="group" type="text" class="form-control" 
								value="{{ $booking['group'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="relayITSTV" class="col-md-4 col-form-label text-md-left">{{ __('Relay ke ITS TV') }}</label>
						<i class="fa fa-sticky-note-o booking"></i>
						<label for="relayITSTV" class="col-md-4 col-form-label text-md-left">{{ __('Layanan Live Youtube ITS') }}</label>
						<i class="fa fa-sticky-note-o"></i>
						<div class="col-md-6">
							<div id="relayITSTV">{{ $booking['relay_ITSTV']?'Iya':'Tidak' }}</div>
							<!-- <label for="iya">Iya</label>
							<label for="tidak">Tidak</label> -->
						</div>
					</div>

					<div class="form-group row">
						<label for="pesertaBanyak" class="col-md-4 col-form-label text-md-left">{{ __('Peserta sebanyak 500 atau lebih') }}</label>
						<i class="fa fa-sticky-note-o"></i>
						<div class="col-md-6">
							<div id="pesertaBanyak">{!! $booking['peserta_banyak']==false?'&le; 500':'501 - 1000' !!}</div>
						</div>
						{{-- <sub>Jawaban iya mengurangi kemungkinan di approve karena kurangnya sumber daya</sub> --}}
					</div>

					<div class="form-group row">
						<label for="disetujui" class="col-md-4 col-form-label text-md-left">{{ __('Current Approval Status') }}</label>
						<i class="fa fa-address-card"></i>
						<div class="col-md-6">
							<input 
								id="disetujui" type="text" class="form-control" 
								value="{{ null!==$booking['disetujui'] ? ($booking['disetujui']?'Disetjui':'Ditolak'):'' }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="alasan" class="col-md-4 col-form-label text-md-left">{{ __('Alasan') }}</label>
						<i class="fa fa-sticky-note-o"></i>
						<div class="col-md-6">
							<textarea id="alasan" type="text" class="form-control" disabled> {{ $booking['deskripsi_disetujui'] }}</textarea>
						</div>
					</div>
					@endif
					@if($isAdmin)
					<form method="POST" action="{{ route('booking.verify') }}">
						@csrf
						<input name="id" type="hidden" value="{{ $booking['id'] }}">
						<input id="verify" name="verify" type="hidden" value="">

						<div class="form-group row">
							<label for="hostNama" class="col-md-4 col-form-label text-md-left">{{ __('Host Name') }}</label>
							<i class="fa fa-building"></i>
							<div class="col-md-6">
								<input 
									id="hostNama" type="text" class="form-control" name="hostNama" 
									value="{{ old('hostNama')??$booking['api_host_nama'] }}" required
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="hostEmail" class="col-md-4 col-form-label text-md-left">{{ __('Host Email') }}</label>
							<i class="fa fa-envelope-o"></i>
							<div class="col-md-6">
								<input 
									id="hostEmail" type="email" class="form-control" name="hostEmail" 
									value="{{ old('hostEmail')??$booking['api_host_email'] }}" required autocomplete="email"
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="alasan" class="col-md-4 col-form-label text-md-left">{{ __('Alasan') }}</label>
							<i class="fa fa-sticky-note-o"></i>
							<div class="col-md-6">
								<textarea id="alasan" type="text" class="form-control" name="alasan">{{ old('alasan') }}</textarea>
							</div>
						</div>
	
						<div class="form-group row mb-0">
							<div class="col-md-8 offset-md-4">
								<button type="button" class="btn btn-submit" onclick="setujuBooking()">
									{{ __('Setujui Booking') }}
								</button>
								<button type="button" class="btn btn-danger" onclick="tolakBooking()">
									{{ __('Tolak Booking') }}
								</button>
							</div>
						</div>
					</form>
					@endif
					<!-- @if($isOwner)
					<div class="form-group row mb-0">
							<div class="col-md-8 offset-md-4">
								<button style="z-index: 9999" href="{{ url('/booking/waitinglist') }}" type="submit" class="btn btn-submit">
									{{ __('Back') }}
								</button>

							</div>
						</div>
					@endif -->
				</div>
			</div>
		</div>
	</div>

@if($isAdmin)
<script src="{{ asset('js/booking/verify.js') }}" defer></script>
@endif
<script src="{{ asset('js/booking/durasi.js') }}" defer></script>
@endsection
