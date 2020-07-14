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

					@if(Route::is('booking.new'))
					<form method="POST" action="{{ route('booking.new') }}">
					@elseif(Route::is('booking.edit'))
					<form method="POST" action="{{ route('booking.edit') }}">
						<input name="id" type="hidden" value="{{ $id }}">
					@endif
						@csrf

						<div class="form-group row">
							<label for="namaAcara" class="col-md-4 col-form-label text-md-left">{{ __('Nama Acara') }}</label>
							<i class="fa fa-sticky-note-o booking"></i>
							<div class="col-md-6">
								<input 
									id="namaAcara" type="text" class="form-control" name="namaAcara" 
									value="{{ old('namaAcara')??$booking['nama_acara'] }}" required autofocus
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="unitDepartemen" class="col-md-4 col-form-label text-md-left">{{ __('Unit/Departemen') }}</label>
							<i class="fa fa-building booking"></i>
							<div class="col-md-6">
								<input 
									id="unitDepartemen" type="text" class="form-control" name="unitDepartemen" 
									value="{{ old('unitDepartemen')??$booking['unit'] }}" required
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="namaAnda" class="col-md-4 col-form-label text-md-left">{{ __('Nama Anda') }}</label>
							<i style="padding-left: 1px" class="fa fa-user booking"></i>
							<!-- style="margin-left: 1px" -->
							<div class="col-md-6">
								<input 
									id="namaAnda" type="text" class="form-control" name="namaAnda" 
									value="{{ old('namaAnda')??$booking['nama_booker'] }}" required
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="emailITS" class="col-md-4 col-form-label text-md-left">{{ __('Email ITS') }}</label>
							<i class="fa fa-envelope-o booking"></i>
							<div class="col-md-6">
								<input 
									id="emailITS" type="email" class="form-control" name="emailITS" 
									value="{{ old('emailITS')??$booking['email_its'] }}" required autocomplete="email"
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="userIntegra" class="col-md-4 col-form-label text-md-left">{{ __('User Integra') }}</label>
							<i class="fa fa-address-card booking"></i>
							<div class="col-md-6">
								<input 
									id="userIntegra" type="text" class="form-control" name="userIntegra" 
									value="{{ old('userIntegra')??$booking['user_integra'] }}" required
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="waktuMulai" class="col-md-4 col-form-label text-md-left">{{ __('Waktu Mulai Webinar') }}</label>
							<i class="fa fa-calendar-o booking"></i>
							<div class="col-md-6">
								<input 
									id="waktuMulai" type="datetime-local" class="form-control" name="waktuMulai" 
									value="{{ old('waktuMulai')??$booking['waktu_mulai'] }}" required
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="waktuSelesai" class="col-md-4 col-form-label text-md-left">{{ __('Waktu Selesai Webinar') }}</label>
							<i class="fa fa-calendar"></i>
							<div class="col-md-6">
								<input 
									id="waktuSelesai" type="datetime-local" class="form-control" name="waktuSelesai" 
									value="{{ old('waktuSelesai')??$booking['waktu_akhir'] }}" onchange="onupdateWaktu()" required
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="durasi" class="col-md-4 col-form-label text-md-left">{{ __('Durasi Webinar') }}</label>
							<i class="fa fa-clock-o"></i>
							<div class="col-md-6">
								<input id="durasi" type="text" class="form-control" value="" onchange="onupdateDurasi()"> jam
							</div>
						</div>

						<div class="form-group row">
							<label for="civitasAkademik" class="col-md-4 col-form-label text-md-left">{{ __('Civitas Akademik') }}</label>
							<i class="fa fa-users booking"></i>
							<div class="col-md-6">
								<select name="civitasAkademik" id="civitasAkademik" class="form-control">
									@foreach ($civitas as $item)
									<option 
										value="{{$item['nama']}}"
										{{ (old('civitasAkademik')??$booking['civitas']) == $item['nama'] ? 'selected':'' }}
									>{{$item['nama']}}</option>
									@endforeach
								</select>
							</div>
						</div>

						<div class="form-group row">
							<label for="relayITSTV" class="col-md-4 col-form-label text-md-left">{{ __('Relay ke ITS TV') }}</label>
							<div class="col-md-6">
								<input 
									id="relayITSTV" type="checkbox" class="" name="relayITSTV" 
									value="relayITSTVBoolean" {{ $booking['relay_ITSTV']?'checked':'' }}
								> 
								<!-- <label for="iya">Iya</label>
								<label for="tidak">Tidak</label> -->
							</div>
							<div class="col-md-6">
								<sub class="">Webinar akan direlay sesuai dengan persetujuan ......</sub>
							</div>	
						</div>

						<div class="form-group row">
							<label for="pesertaBanyak" class="col-md-4 col-form-label text-md-left">{{ __('Peserta sebanyak 500 atau lebih') }}</label>
							<div class="col-md-6">
								<input 
									id="500" type="radio" class="" name="pesertaBanyak" 
									value="500" {{ $booking['peserta_banyak']==false?'checked':'' }}
								><div>< 500</div>
								<input id="1000" type="radio" class="" name="pesertaBanyak" 
									value="1000" {{ $booking['peserta_banyak']==true?'checked':'' }}
								><div>500 - 1000</div>
								</div>
							{{-- <sub>Jawaban iya mengurangi kemungkinan di approve karena kurangnya sumber daya</sub> --}}
						</div>


						<div class="form-group row mb-0">
							<div class="col-md-8 offset-md-4">
								<button type="submit" class="btn btn-submit">
									@if(Route::is('booking.new'))
									{{ __('Submit Booking') }}
									@elseif(Route::is('booking.edit'))
									{{ __('Edit Booking') }}
									@endif
								</button>

							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
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
