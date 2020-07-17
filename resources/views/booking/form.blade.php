@extends('layouts.user')

@section('content')

<!-- <div class="container"> -->
	<!-- <div class="row justify-content-center"> -->
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

					@if(Route::is('booking.new'))
					<form method="POST" action="{{ route('booking.new') }}">
					@elseif(Route::is('booking.edit'))
					<form method="POST" action="{{ route('booking.edit') }}">
						<input name="id" type="hidden" value="{{ $booking['id'] }}">
					@endif
						@csrf

						<div class="form-group row">
							<label for="namaPic" class="col-md-4 col-form-label text-md-left">{{ __('Nama PIC') }}</label>
							<i class="fa fa-sticky-note-o booking"></i>
							<div class="col-md-6">
								<input 
									id="namaPic" type="text" class="form-control" name="namaPic" 
									value="{{ old('namaPic')??$booking['nama_pic'] }}" required autofocus
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="integraPic" class="col-md-4 col-form-label text-md-left">{{ __('User Integra PIC') }}</label>
							<i class="fa fa-sticky-note-o booking"></i>
							<div class="col-md-6">
								<input 
									id="integraPic" type="text" class="form-control" name="integraPic" 
									value="{{ old('integraPic')??$booking['integra_pic'] }}" required
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="emailPic" class="col-md-4 col-form-label text-md-left">{{ __('Email ITS PIC') }}</label>
							<i class="fa fa-sticky-note-o booking"></i>
							<div class="col-md-6">
								<input 
									id="emailPic" type="text" class="form-control" name="emailPic" 
									value="{{ old('emailPic')??$booking['email_pic'] }}" required
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="sivitas" class="col-md-4 col-form-label text-md-left">{{ __('Sivitas Akademika') }}</label>
							<i class="fa fa-sticky-note-o booking"></i>
							<div class="col-md-6">
								<input 
									id="sivitas" type="text" class="form-control" name="sivitas" 
									value="{{ old('sivitas')??$booking['sivitas'] }}" required
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="departemenUnit" class="col-md-4 col-form-label text-md-left">{{ __('Departemen/Unit') }}</label>
							<i class="fa fa-sticky-note-o booking"></i>
							<div class="col-md-6">
								<input 
									id="departemenUnit" type="text" class="form-control" name="departemenUnit" 
									value="{{ old('departemenUnit')??$booking['unit'] }}" required
								>
							</div>
						</div>	

						<div class="form-group row">
							<label for="noWa" class="col-md-4 col-form-label text-md-left">{{ __('No. WA') }}</label>
							<i class="fa fa-sticky-note-o booking"></i>
							<div class="col-md-6">
								<input 
									id="noWa" type="tel" class="form-control" name="noWa" autocomplete="tel"
									value="{{ old('noWa')??$booking['no_wa'] }}" required
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="namaAcara" class="col-md-4 col-form-label text-md-left">{{ __('Nama Acara') }}</label>
							<i class="fa fa-sticky-note-o booking"></i>
							<div class="col-md-6">
								<input 
									id="namaAcara" type="text" class="form-control" name="namaAcara" 
									value="{{ old('namaAcara')??$booking['nama_acara'] }}" required
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="penyelengaraAcara" class="col-md-4 col-form-label text-md-left">{{ __('Penyelengara Acara') }}</label>
							<i class="fa fa-sticky-note-o booking"></i>
							<div class="col-md-6">
								<input id="orgDatas" hidden
									data-types="{{json_encode($orgTypes)}}" 
									data-orgs="{{json_encode($organisasis)}}" 
									data-curtypeid="{{json_encode($booking['org_type_id'])}}" 
									data-curorgid="{{json_encode($booking['org_id'])}}"
								>
								<select name="penyelengaraAcaraTypes" id="penyelengaraAcaraTypes" class="form-control">
								</select>
								<select name="penyelengaraAcara" id="penyelengaraAcara" class="form-control">
								</select>
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
							<i class="fa fa-calendar booking"></i>
							<div class="col-md-6">
								<input 
									id="waktuSelesai" type="datetime-local" class="form-control" name="waktuSelesai" 
									value="{{ old('waktuSelesai')??$booking['waktu_akhir'] }}" onchange="onupdateWaktu()" required
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="durasi" class="col-md-4 col-form-label text-md-left">{{ __('Durasi Webinar') }}</label>
							<i class="fa fa-clock-o booking"></i>
							<div class="col-md-6">
								<input id="durasi" type="text" class="form-control" value="" onchange="onupdateDurasi()"> jam
							</div>
						</div>

						<div class="form-group row">
							<label for="namaAnda" class="col-md-4 col-form-label text-md-left">{{ __('Nama Anda') }}</label>
							<i style="padding-left: 1px" class="fa fa-user booking"></i>
							<!-- style="margin-left: 1px" -->
							<div class="col-md-6">
								<input 
									id="namaAnda" type="text" class="form-control" 
									value="{{ $booking['reg_nama'] }}" required disabled
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="emailITS" class="col-md-4 col-form-label text-md-left">{{ __('Email ITS') }}</label>
							<i class="fa fa-envelope-o booking"></i>
							<div class="col-md-6">
								<input 
									id="emailITS" type="email" class="form-control" 
									value="{{ $booking['reg_email'] }}" required autocomplete="email" disabled
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="userIntegra" class="col-md-4 col-form-label text-md-left">{{ __('User Integra') }}</label>
							<i class="fa fa-address-card booking"></i>
							<div class="col-md-6">
								<input 
									id="userIntegra" type="text" class="form-control" 
									value="{{ $booking['reg_integra'] }}" required disabled
								>
							</div>
						</div>

						<div class="form-group row">
							<label for="group" class="col-md-4 col-form-label text-md-left">{{ __('Group') }}</label>
							<i class="fa fa-address-card booking"></i>
							<div class="col-md-6">
								<input 
									id="group" type="text" class="form-control" 
									value="{{ $booking['group'] }}" required disabled
								>
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
									id="500" type="radio" class="form-radio" name="pesertaBanyak" 
									value="500" {{ $booking['peserta_banyak']==false?'checked':'' }}
								><div class="form-option">< 500</div>
								<input id="1000" type="radio" class="form-radio" name="pesertaBanyak" 
									value="1000" {{ $booking['peserta_banyak']==true?'checked':'' }}
								><div class="form-option">500 - 1000</div>
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
<!-- </div> -->
<script src="{{ asset('js/booking/durasi.js') }}" defer></script>
<script src="{{ asset('js/booking/organisasis.js') }}" defer></script>
@endsection
