@extends('layouts.user')

@section('content')

	<div class="right_col booking" role="main">
		<div class="col-md-12 col-sm-12">
			<div class="card">
					<ul id="section-tabs">
							<li id="satu" class="current active">Data PIC Zoom</li>
							<li id="dua">Informasi</li>
					</ul>

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
					<form id="bookingForm" method="POST" action="{{ route('booking.new') }}">
					@elseif(Route::is('booking.edit'))
					<form id="bookingForm" method="POST" action="{{ route('booking.edit') }}">
						<input name="id" type="hidden" value="{{ $booking['id'] }}">
					@endif
						@csrf

						<div id="one" class="tab">
							<div class="form-group row">
								<label for="namaPic" class="col-md-4 col-form-label text-md-left">{{ __('Nama') }}<p style="color: red" class="d-inline">*</p></label>
								<i style="padding-left: 1px" class="fa fa-user booking"></i>
								<div class="col-md-6">
									<input 
										id="namaPic" type="text" class="form-control" 
										value="{{ $booking['nama_pic'] }}" disabled
									>
								</div>
							</div>

							<div class="form-group row">
								<label for="integraPic" class="col-md-4 col-form-label text-md-left">{{ __('User Integra') }}<p style="color: red" class="d-inline">*</p></label>
								<i class="fa fa-address-card booking"></i>
								<div class="col-md-6">
									<input 
										id="integraPic" type="text" class="form-control"  
										value="{{ $booking['integra_pic'] }}" disabled
									>
								</div>
							</div>

							<div class="form-group row">
								<label for="emailPic" class="col-md-4 col-form-label text-md-left">{{ __('Email ITS') }}<p style="color: red" class="d-inline">*</p></label>
								<i class="fa fa-envelope booking"></i>
								<div class="col-md-6">
									<input 
										id="emailPic" type="text" class="form-control" 
										value="{{ $booking['email_pic'] }}" disabled
									>
								</div>
							</div>

							<div class="form-group row">
								<label for="sivitas" class="col-md-4 col-form-label text-md-left">{{ __('Sivitas Akademika') }}<p style="color: red" class="d-inline">*</p></label>
								<i class="fa fa-users booking"></i>
								<div class="col-md-6">
									<input 
										id="sivitas" type="text" class="form-control" 
										value="{{ $booking['sivitas'] }}" disabled
									>
								</div>
							</div>

							<div class="form-group row">
								<label for="noWa" class="col-md-4 col-form-label text-md-left">{{ __('No. WA') }}<p style="color: red" class="d-inline">*</p></label>
								<i class="fa fa-mobile fa-2x booking"></i>
								<div class="col-md-6">
									<input 
										id="noWa" type="tel" class="form-control" name="noWa" autocomplete="tel" placeholder="Contoh: 089605606878"
										value="{{ old('noWa')??$booking['no_wa'] }}" required autofocus
									>
								</div>
							</div>
							<div class="form-group row">
							<button style="position: absolute;bottom: 15px;right: 50px;" type="button" id="nextBtn" class="btn btn-submit next-btn" onclick="nextPrev(1)">Next</button>
							</div>
						</div>

						<div id="two" class="tab">
							<div class="form-group row">
								<label for="namaAcara" class="col-md-4 col-form-label text-md-left">{{ __('Nama Acara') }}<p style="color: red" class="d-inline">*</p></label>
								<i class="fa fa-sticky-note-o booking"></i>
								<div class="col-md-6">
									<input 
										id="namaAcara" type="text" class="form-control" name="namaAcara" 
										value="{{ old('namaAcara')??$booking['nama_acara'] }}" required
									>
								</div>
							</div>

							<div class="form-group row">
								<label for="penyelengaraAcara" class="col-md-4 col-form-label text-md-left">{{ __('Penyelengara Acara') }}<p style="color: red" class="d-inline">*</p></label>
								<i class="fa fa-user-circle booking"></i>
								<div class="col-md-6">
									<input id="unitDatas" hidden
										data-types="{{json_encode($unitTypes)}}" 
										data-units="{{json_encode($units)}}" 
										data-curtypeid="{{json_encode($booking['unit_type_id'])}}" 
										data-curunitid="{{json_encode($booking['unit_id'])}}"
									>
									<select name="penyelengaraAcaraTypes" id="penyelengaraAcaraTypes" class="form-control">
									</select>
									</br>
									<select name="penyelengaraAcara" id="penyelengaraAcara" class="form-control">
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label for="waktuMulai" class="col-md-4 col-form-label text-md-left">{{ __('Waktu Mulai Webinar') }}<p style="color: red" class="d-inline">*</p></label>
								<i class="fa fa-calendar-o booking"></i>
								<div class="col-md-6">
									<input 
										id="waktuMulai" type="datetime-local" class="form-control" name="waktuMulai" 
										value="{{ old('waktuMulai')??$booking['waktu_mulai'] }}" required
									>
								</div>
							</div>

							<div class="form-group row">
								<label for="waktuSelesai" class="col-md-4 col-form-label text-md-left">{{ __('Waktu Selesai Webinar') }}<p style="color: red" class="d-inline">*</p></label>
								<i class="fa fa-calendar booking"></i>
								<div class="col-md-6">
									<input 
										id="waktuSelesai" type="datetime-local" class="form-control" name="waktuSelesai" 
										value="{{ old('waktuSelesai')??$booking['waktu_akhir'] }}" onchange="onupdateWaktu()" required
									>
								</div>
							</div>

							<div class="form-group row">
								<label for="durasi" class="col-md-4 col-form-label text-md-left">{{ __('Durasi Webinar') }}<p style="color: red" class="d-inline">*</p></label>
								<i class="fa fa-clock-o booking"></i>
								<div class="col-md-6">
									<input id="durasi" type="text" style="width:200px;" class="form-control d-inline" value="" onchange="onupdateDurasi()">
										Jam
								</div>
							</div>

							<div class="form-group row">
								<label for="relayITSTV" class="col-md-4 col-form-label text-md-left">{{ __('Layanan Live Youtube ITS') }}</label>
								<div class="col-md-6">
									<input 
										id="relayITSTV" type="checkbox" class="" name="relayITSTV" 
										value="relayITSTVBoolean" {{ $booking['relay_ITSTV']?'checked':'' }}
									> 
								</div>
								<div class="col-md-6">
									<sub class="">Silahkan menghubungi Unit Komunikasi Publik (UKP) pada <a href="https://servicedesk.its.ac.id/" target="_blank">
									</br>servicedesk.its.ac.id</a> untuk permohonan Layanan Live Youtube ITS.</sub>
								</div>	
							</div>

							<div class="form-group row">
								<label for="pesertaBanyak" class="col-md-4 col-form-label text-md-left">{{ __('Peserta sebanyak 500 atau lebih') }}<p style="color: red" class="d-inline">*</p></label>
								<div class="col-md-6">
									<input 
										id="500" type="radio" class="form-radio" name="pesertaBanyak" 
										value="500" {{ $booking['peserta_banyak']==false?'checked':'' }}
									><div class="form-option">&le; 500</div>
									<input id="1000" type="radio" class="form-radio" name="pesertaBanyak" 
										value="1000" {{ $booking['peserta_banyak']==true?'checked':'' }}
									><div class="form-option">501 - 1000</div>
								</div>
								{{-- <sub>Jawaban iya mengurangi kemungkinan di approve karena kurangnya sumber daya</sub> --}}
							</div>
						<!-- </div> -->
							<div class="form-group row mb-0">
								<div class="col-md-8 offset-md-4">
								<button style="position: absolute;bottom: 15px;right: 200px;" type="button" id="prevvBtn" class="btn btn-submit next-btn" onclick="nextPrev(-1)">Previous</button>
									<button style="position: absolute;bottom: 15px;right: 50px;" type="submit" id="submitBtn" class="btn btn-submit">
										@if(Route::is('booking.new'))
										{{ __('Submit Booking') }}
										@elseif(Route::is('booking.edit'))
										{{ __('Edit Booking') }}
										@endif
									</button>

								</div>
							</div>
						</div>

						<!-- </div> -->
					</form>
				</div>
			</div>
		</div>
	</div>
<!-- </div> -->
<script src="{{ asset('js/booking/durasi.js') }}" defer></script>
<script src="{{ asset('js/booking/units.js') }}" defer></script>
@endsection
