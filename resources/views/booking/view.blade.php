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
						<label for="namaAcara" class="col-md-4 col-form-label text-md-left">{{ __('Nama Acara') }}</label>
						<i class="fa fa-sticky-note-o booking"></i>
						<div class="col-md-6">
							<textarea style="resize: none;" rows="2" cols="30" 
								id="namaAcara" type="text" class="form-control" disabled>{{ $booking->nama_acara }}
							</textarea>
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
						</br>
							<input 
								id="penyelengaraAcara" type="text" class="form-control" 
								value="{{ $booking->unit }}" disabled
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
					@endif

					<div class="form-group row">
						<label for="waktuMulai" class="col-md-4 col-form-label text-md-left">{{ __('Waktu Mulai Webinar') }}</label>
						<i class="fa fa-calendar-o booking"></i>
						<div class="col-md-6">
							<input 
								id="waktuMulai" type="text" class="form-control" 
								value="{{ $booking->waktu_mulai }}" disabled
							>
						</div>
					</div>
					
					<input id="waktuSelesai" type="hidden" name="waktuSelesai" value="{{ old('waktuSelesai')??$booking['waktu_akhir'] }}" required>

					<div class="form-group row">
						<label for="durasi" class="col-md-4 col-form-label text-md-left">{{ __('Durasi Webinar') }}</label>
						<i class="fa fa-clock-o booking"></i>
						<div class="col-md-6">
							<input id="durasi" type="text" class="form-control" value="" onchange="onupdateDurasi()" disabled> <div>jam</div>
						</div>
					</div>

					@if($isOwner || $isAdmin)
					<div class="form-group row">
						<label for="relayITSTV" class="col-md-4 col-form-label text-md-left">{{ __('Layanan Live Youtube ITS') }}</label>
						<i class="fa fa-sticky-note-o booking"></i>
						<div class="col-md-6">
							<div id="relayITSTV">{{ $booking->relay_ITSTV?'Iya':'Tidak' }}</div>
							<!-- <label for="iya">Iya</label>
							<label for="tidak">Tidak</label> -->
						</div>
					</div>

					<div class="form-group row">
						<label for="pesertaBanyak" class="col-md-4 col-form-label text-md-left">{{ __('Peserta sebanyak 500 atau lebih') }}</label>
						<i class="fa fa-sticky-note-o  booking"></i>
						<div class="col-md-6">
							<div id="pesertaBanyak">{!! $booking->peserta_banyak==false?'&le; 500':'501 - 1000' !!}</div>
						</div>
						{{-- <sub>Jawaban iya mengurangi kemungkinan di approve karena kurangnya sumber daya</sub> --}}
					</div>

					<div class="form-group row">
						<label for="disetujui" class="col-md-4 col-form-label text-md-left">{{ __('Current Approval Status') }}</label>
						<i class="fa fa-address-card booking"></i>
						<div class="col-md-6">
							<input 
								id="disetujui" type="text" class="form-control" s
								value="{{ null!==$booking->disetujui ? ($booking->disetujui?'Disetujui':'Ditolak'):'' }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="alasan" class="col-md-4 col-form-label text-md-left">{{ __('Alasan') }}</label>
						<i class="fa fa-sticky-note-o booking"></i>
						<div class="col-md-6">
							<textarea id="alasan" type="text" class="form-control" disabled> {{ $booking->deskripsi_disetujui }}</textarea>
						</div>
					</div>
					@endif

					@if($isAdmin)
					<form method="POST" action="{{ route('booking.accept', ['id' => $booking->id]) }}">
						@csrf
						<div class="form-group row">
							<label for="hostEmail" class="col-md-4 col-form-label text-md-left">{{ __('Host Account') }}</label>
							<i class="fa fa-envelope-o booking"></i>
							<div class="col-md-6">
								{{ isset($booking->api_host_email)?'Last picked: '.$booking->api_host_email:'' }}
								<select name="hostEmail" id="hostEmail" class="form-control">
									<option value="500 (1)">500 (1)</option>
									<option value="500 (2)">500 (2)</option>
									<option value="1000 (1)">1000 (1)</option>
								</select>
							</div>
						</div>
						<div class="form-group row mb-0">
							<div class="col-md-8 offset-md-4">
								@if($booking->disetujui === null)
								<button type="submit" class="btn btn-submit">
									{{ __('Setujui Booking') }}
								</button>
								<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#denyModal" onclick="modalPopulate()">
									{{ __('Tolak Booking') }}
								</button>
								@else
								<button type="button" class="btn btn-warning" onclick="cancelBooking()">
									{{ __('Cancel Booking') }}
								</button>
								@endif
							</div>
						</div>
					</form>
					@if($booking->disetujui !== null)
						<form id="cancelForm" method="POST" action="{{ route('booking.cancel', ['id' => $booking->id]) }}">
							@csrf
						</form>
						@endif
						<script type="text/javascript" >
							function cancelBooking() {
								document.getElementById('cancelForm').submit();
							}
							function modalPopulate() {
								let unitNama = document.getElementById('unitNama').value;
								let unitTypeSelElm = document.getElementById('unitType');
								let unitType = unitTypeSelElm.options[unitTypeSelElm.selectedIndex].innerText;
								if (unitTypeSelElm.selectedIndex == 0) {
									alert("Mohon pilih tipe unit");
									return;
								}
								let text = `Tambahkan ${unitNama} kategori ${unitType} ke database?`;
								document.getElementById('confirmationText').innerText = text;
								
								document.getElementById('modalUnitNama').value = unitNama;
								document.getElementById('modalUnitType').value = unitTypeSelElm.value;
						
							}
						
						</script>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="denyModal" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="denyModalLabel">Konfirmasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
			</div>
			<form action="{{route('booking.deny', ['id' => $booking->id])}}" method="POST">
				@csrf
				<input name="id" type="hidden" value="{{ $booking->id }}">
				<input name="verify" type="hidden" value="tolak">
				<div class="modal-body">
					<div class="form-group row">
						<label for="alasan" class="col-md-4 col-form-label text-md-left">{{ __('Alasan') }}</label>
						<i class="fa fa-sticky-note-o booking"></i>
						<div class="col-md-6">
							<textarea id="alasan" type="text" class="form-control" name="alasan">{{ old('alasan') }}</textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-danger">Tolak Booking</button>
				</div>
			</form>
    </div>
  </div>
</div>

<script src="{{ asset('js/booking/durasiView.js') }}" defer></script>
@endsection
