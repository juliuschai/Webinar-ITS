@extends('layouts.header')

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
					@if (session()->has('message'))
					<div class="alert alert-success">
						<ul>
							<li>{{ session()->get('message') }}</li>
						</ul>
					</div>
					@endif

					@if($isOwner || $isAdmin)
					<div class="form-group row">
						<label for="namaPic" class="col-md-4 col-form-label text-md-left">{{ __('Nama PIC Zoom') }}</label>
						<i tyle="padding-left: 1px" class="fa fa-user booking"></i>
						<div class="col-md-6">
							<input
								id="namaPic" type="text" class="form-control"
								value="{{ $booking->nama_pic }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="integraPic" class="col-md-4 col-form-label text-md-left">{{ __('User Integra PIC Zoom') }}</label>
						<i class="fa fa-address-card booking"></i>
						<div class="col-md-6">
							<input
								id="integraPic" type="text" class="form-control"
								value="{{ $booking->integra_pic }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="emailPic" class="col-md-4 col-form-label text-md-left">{{ __('Email ITS PIC Zoom') }}</label>
						<i class="fa fa-envelope booking"></i>
						<div class="col-md-6">
							<input
								id="emailPic" type="text" class="form-control"
								value="{{ $booking->email_pic }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="sivitas" class="col-md-4 col-form-label text-md-left">{{ __('Sivitas Akademika') }}</label>
						<i class="fa fa-users booking"></i>
						<div class="col-md-6">
							<input
								id="sivitas" type="text" class="form-control"
								value="{{ $booking->sivitas }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="noWa" class="col-md-4 col-form-label text-md-left">{{ __('No. WA') }}</label>
						<i class="fa fa-mobile fa-2x booking"></i>
						<div class="col-md-6">
							<input
								id="noWa" type="tel" class="form-control"
								value="{{ $booking->no_wa }}" disabled
							>
						</div>
					</div>
					@endif

					<div class="form-group row">
						<label for="kategoriAcara" class="col-md-4 col-form-label text-md-left">{{ __('Kategori Acara') }}</label>
						<i class="fa fa-list-alt booking"></i>
						<div class="col-md-6">
							<input
								type="text" class="form-control"
								value="{{ $booking->kategori?$booking->kategori->nama:'' }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="namaAcara" class="col-md-4 col-form-label text-md-left">{{ __('Nama Acara') }}</label>
						<i class="fa fa-sticky-note-o booking"></i>
						<div class="col-md-6">
							<textarea style="resize: none;" rows="2" cols="30"
								id="namaAcara" type="text" class="form-control" disabled>{{ $booking->nama_acara }}</textarea>
						</div>
					</div>

					<div class="form-group row">
						<label for="penyelengaraAcaraType" class="col-md-4 col-form-label text-md-left">{{ __('Penyelengara Acara') }}</label>
						<i class="fa fa-building booking"></i>
						<div class="col-md-6">
							<input
								id="penyelengaraAcaraType" type="text" class="form-control"
								value="{{ $booking->unit_type }}" disabled
							>
						<br>
							<input
								id="penyelengaraAcara" type="text" class="form-control"
								value="{{ $booking->unit }}" disabled
							>
						</div>
					</div>

					@if($isOwner || $isAdmin)
					<div class="form-group row">
						<label class="col-md-4 col-form-label text-md-left">{{ __('Admin DPTSI') }}</label>
						<i class="fa fa-list-alt booking"></i>
						<div class="col-md-6">
							<input
								type="text" class="form-control"
								value="{{$booking->admin_dptsi_nama.' - '.$booking->admin_dptsi_no_wa}}" disabled
							>
						</div>
					</div>

					@if(isset($booking->file_pendukung))
					<div class="form-group row">
						<label class="col-md-4 col-form-label text-md-left"></label>
						<i class="fa fa-user-circle booking"></i>
						<div class="col-md-6">
							<a href="{{route('dokumen.get', ['id' => $booking->id])}}" target="_blank"><button style="background-color: #0067ac !important; color: white !important;" class="btn">View</button></a>
							<a href="{{route('dokumen.download', ['id' => $booking->id])}}" target="_blank"><button style="background-color: #0067ac !important; color: white !important;" class="btn">Download</button></a>
						</div>
					</div>
					@endif {{-- end if file_pendukung --}}
					@endif

					@php($gladiCount=0)
					@foreach ($booking_times as $book_time)
					<hr style="width:100%;text-align:left;margin-top:5px;margin-bottom:10px">

					<div class="d-flex flex-row-reverse">
						@if($book_time->gladi == '1')
						<div id="sesi" class="btn btn-outline-info" style="padding: 2px 10px; font-size:11px; margin-bottom: 10px;">Sesi Gladi @php($gladiCount++){{$gladiCount}}</div>
						@else
						<div id="sesi" class="btn btn-outline-info" style="padding: 2px 10px; font-size:11px; margin-bottom: 10px;">Sesi Webinar {{$loop->index - $gladiCount+1}}</div>
						@endif
					</div>
					<div class="form-group row">

					<label class="col-md-4 col-form-label text-md-left">{{ __('Waktu Mulai Webinar') }}</label>
						<i class="fa fa-calendar-o booking"></i>
						<div class="col-md-6">
							<input
								type="text" class="form-control waktuMulaiDisplay"
								value="" disabled
							>
						</div>
					</div>

					<input type="hidden" class="waktuMulai" value="{{$book_time->waktu_mulai->format(DateTime::ATOM)}}">
					<input type="hidden" class="waktuSelesai" value="{{ $book_time->waktu_akhir->format(DateTime::ATOM) }}">

					<div class="form-group row">
						<label class="col-md-4 col-form-label text-md-left">{{ __('Durasi Webinar') }}</label>
						<i class="fa fa-clock-o booking"></i>
						<div class="col-md-6">
							<input type="text" class="form-control durasi" disabled> <div>jam</div>
						</div>
					</div>

					@if($isOwner || $isAdmin)
					<div class="form-group row">
						<label class="col-md-4 col-form-label text-md-left">{{ __('Layanan Live Youtube ITS') }}</label>
						<i class="fa fa-sticky-note-o booking"></i>
						<div class="col-md-6">
							<div>{{ $book_time->relay_ITSTV?'Iya':'Tidak' }}</div>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-4 col-form-label text-md-left">{{ __('Peserta sebanyak 500 atau lebih') }}</label>
						<i class="fa fa-sticky-note-o  booking"></i>
						<div class="col-md-6">
							<div>{!! $book_time->max_peserta == 500?'&le; 500':'501 - 1000' !!}</div>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-md-4 col-form-label text-md-left">{{ __('Current Approval Status') }}</label>
						<i class="fa fa-address-card booking"></i>
						<div class="col-md-6">
							<input
								type="text" class="form-control"
								value="{{ isset($book_time->disetujui)?($book_time->disetujui?'Disetujui':'Ditolak'):'' }}" disabled
							>
						</div>
					</div>
					@endif
					@if($isAdmin)
					<div class="action" data-id="{{$book_time->id}}">
						<select class="hostAccount form-control col-sm-4 d-inline" style="width:10%;font-size:12px;margin-right:24%;">
							<option value="" style="display: none;">Nothing</option>
							@foreach ($book_time->host_accounts as $host_account)
							<option value="{{$host_account->id}}" {{$book_time->host_account_id==$host_account->id?'selected':''}}>{{$host_account->nama}}</option>
							@endforeach
						</select>
						<input
							type="hidden" class="status"
							value="{{isset($book_time->disetujui) ? ($book_time->disetujui?'accept':'deny') :''}}"
						>
						<button type="button" style="margin-bottom:5px;" class="btn btn-submit acceptButton" onclick="acceptBooking(this)" style="display: none;">
							{{ __('Setujui Booking') }}
						</button>
						<button type="button" style="margin-bottom:5px;" class="btn btn-danger denyButton" onclick="denyBooking(this)" style="display: none;">
							{{ __('Tolak Booking') }}
						</button>
						<button type="button" style="margin-bottom:5px;" class="btn btn-warning cancelButton" onclick="cancelBooking(this)" style="display: none;">
							{{ __('Cancel Booking') }}
						</button>
					</div>
					@endif
					@endforeach

					@if($isOwner || $isAdmin)
					<div class="form-group row">
					<hr style="width:100%;text-align:left;margin-top:5px;margin-bottom:10px">
						<label for="alasan" class="col-md-4 col-form-label text-md-left">{{ __('Alasan') }}</label>
						<i class="fa fa-sticky-note-o booking"></i>
						<div class="col-md-6">
							<textarea id="alasan" type="text" class="form-control" disabled> {{ $booking->deskripsi_disetujui }}</textarea>
						</div>
					</div>
					@endif

					@if($isAdmin)
					<form id="verifyForm" method="POST" action="{{ route('booking.verify', ['id' => $booking->id]) }}">
						@csrf
						<div class="form-group row">
							<label for="adminDPTSI" class="col-md-4 col-form-label text-md-left">{{ __('Admin DPTSI') }}</label>
								<i class="fa fa-list-alt booking"></i>
								<div class="col-md-6">
									<select name="adminDPTSI" class="form-control">
										@foreach ($admins as $admin)
										<option value="{{$admin->id}}" {{old('adminDPTSI')??$booking->admin_dptsi == $admin->id?'selected':''}}>{{$admin->nama}}</option>
										@endforeach
									</select>
								</div>
						</div>

							<input type="hidden" class="alasanField" name="alasan">
						<div class="fields">
							<input type="hidden" name="verify[0][id]" class="id">
							<input type="hidden" name="verify[0][status]" class="status">
							<input type="hidden" name="verify[0][hostAccount]" class="hostAccount">
						</div>
					</form>
					<div class="form-group row mb-0">
						<div class="col-md-8 offset-md-4">
							<input type="hidden" id="lastDisetujui" value="{{$booking->disetujui?'true':''}}">
							<button type="button" class="btn btn-submit" onclick="submit()">
								{{__('Verifikasi Booking')}}
							</button>
							<button type="button" class="btn btn-danger denyAll" onclick="denyAll()">
								{{ __('Tolak Semua') }}
							</button>
						</div>
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
	<script src="{{ asset('js/booking/view.js') }}" defer></script>
@endsection
