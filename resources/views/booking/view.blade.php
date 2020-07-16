@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
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

					<div class="form-group row">
						<label for="namaAcara" class="col-md-4 col-form-label text-md-left">{{ __('Nama Acara') }}</label>
						<i class="fa fa-sticky-note-o"></i>
						<div class="col-md-6">
							<input 
								id="namaAcara" type="text" class="form-control" name="namaAcara" 
								value="{{ $booking['nama_acara'] }}" disabled autofocus
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="unitDepartemen" class="col-md-4 col-form-label text-md-left">{{ __('Unit/Departemen') }}</label>
						<i class="fa fa-building"></i>
						<div class="col-md-6">
							<input 
								id="unitDepartemen" type="text" class="form-control" name="unitDepartemen" 
								value="{{ $booking['unit'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="namaAnda" class="col-md-4 col-form-label text-md-left">{{ __('Nama Anda') }}</label>
						<i style="padding-left: 1px" class="fa fa-user"></i>
						<!-- style="margin-left: 1px" -->
						<div class="col-md-6">
							<input 
								id="namaAnda" type="text" class="form-control" name="namaAnda" 
								value="{{ $booking['nama_booker'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="emailITS" class="col-md-4 col-form-label text-md-left">{{ __('Email ITS') }}</label>
						<i class="fa fa-envelope-o"></i>
						<div class="col-md-6">
							<input 
								id="emailITS" type="email" class="form-control" name="emailITS" 
								value="{{ $booking['email_its'] }}" disabled autocomplete="email"
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="userIntegra" class="col-md-4 col-form-label text-md-left">{{ __('User Integra') }}</label>
						<i class="fa fa-address-card"></i>
						<div class="col-md-6">
							<input 
								id="userIntegra" type="text" class="form-control" name="userIntegra" 
								value="{{ $booking['user_integra'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="waktuMulai" class="col-md-4 col-form-label text-md-left">{{ __('Waktu Mulai Webinar') }}</label>
						<i class="fa fa-calendar-o"></i>
						<div class="col-md-6">
							<input 
								id="waktuMulai" type="datetime-local" class="form-control" name="waktuMulai" 
								value="{{ $booking['waktu_mulai'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="waktuSelesai" class="col-md-4 col-form-label text-md-left">{{ __('Waktu Selesai Webinar') }}</label>
						<i class="fa fa-calendar"></i>
						<div class="col-md-6">
							<input 
								id="waktuSelesai" type="datetime-local" class="form-control" name="waktuSelesai" 
								value="{{ $booking['waktu_akhir'] }}" onchange="onupdateWaktu()" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="durasi" class="col-md-4 col-form-label text-md-left">{{ __('Durasi Webinar') }}</label>
						<i class="fa fa-clock-o"></i>
						<div class="col-md-6">
							<input id="durasi" type="text" class="form-control" value="" onchange="onupdateDurasi()" disabled> jam
						</div>
					</div>

					<div class="form-group row">
						<label for="group" class="col-md-4 col-form-label text-md-left">{{ __('Group') }}</label>
						<i class="fa fa-users"></i>
						<div class="col-md-6">
							<input 
								id="group" type="text" class="form-control" name="group" 
								value="{{ $booking['group'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="relayITSTV" class="col-md-4 col-form-label text-md-left">{{ __('Relay ke ITS TV') }}</label>
						<div class="col-md-6">
							<input 
								id="relayITSTV" type="text" class="form-control" name="relayITSTV" 
								value="{{ $booking['relay_ITSTV']?'iya':'tidak' }}" disabled
							> 
							<!-- <label for="iya">Iya</label>
							<label for="tidak">Tidak</label> -->
						</div>
					</div>

					<div class="form-group row">
						<label for="pesertaBanyak" class="col-md-4 col-form-label text-md-left">{{ __('Peserta sebanyak 500 atau lebih') }}</label>
						<div class="col-md-6">
							<input 
								id="pesertaBanyak" type="text" class="form-control" name="pesertaBanyak" 
								value="{{ $booking['peserta_banyak']==false?'< 500':'500-1000' }}" disabled
							>
						</div>
						{{-- <sub>Jawaban iya mengurangi kemungkinan di approve karena kurangnya sumber daya</sub> --}}
					</div>

					@if($isOwner || $isAdmin)
					<div class="form-group row">
						<label for="disetujui" class="col-md-4 col-form-label text-md-left">{{ __('Current Approval Status') }}</label>
						<i class="fa fa-address-card"></i>
						<div class="col-md-6">
							<input 
								id="disetujui" type="text" class="form-control" name="disetujui" 
								value="{{ null!==$booking['disetujui'] ? ($booking['disetujui']?'approved':'denied'):'' }}" disabled
							>
						</div>
					</div>
					<div class="form-group row">
						<label for="alasan" class="col-md-4 col-form-label text-md-left">{{ __('Alasan') }}</label>
						<i class="fa fa-sticky-note-o"></i>
						<div class="col-md-6">
							<textarea id="alasan" type="text" class="form-control" name="alasan" disabled> {{ $booking['deskripsi_disetujui'] }}</textarea>
						</div>
					</div>
					@endif
					@if($isAdmin)
					<form method="POST" action="{{ route('booking.verify') }}">
						@csrf
						<input name="id" type="hidden" value="{{ $id }}">
						<input id="verify" name="verify" type="hidden" value="">

						<div class="form-group row">
							<label for="hostName" class="col-md-4 col-form-label text-md-left">{{ __('Host Name') }}</label>
							<i class="fa fa-building"></i>
							<div class="col-md-6">
								<input 
									id="hostName" type="text" class="form-control" name="hostName" 
									value="{{ old('hostName')??$booking['api_host_name'] }}" required
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
								<button type="button" class="btn btn-submit" onclick="acceptBooking()">
									{{ __('Approve Booking') }}
								</button>
								<button type="button" class="btn btn-outline-danger" onclick="denyBooking()">
									{{ __('Deny Booking') }}
								</button>
							</div>
						</div>
					</form>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	function acceptBooking() {
		document.getElementById('verify').value = "accept";
		document.getElementsByTagName('form')[0].submit();
	}
	function denyBooking() {
		document.getElementById('verify').value = "deny";
		document.getElementsByTagName('form')[0].submit();
	}

	function onupdateWaktu() {
		let start = new Date(document.getElementById('waktuMulai').value);
		let end = new Date(document.getElementById('waktuSelesai').value);

		document.getElementById('durasi').value = (end-start)/3600/1000;
	}
	setTimeout(onupdateWaktu, 500);
</script>
@endsection
