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

					@if($isOwner || $isAdmin)
					<div class="form-group row">
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
						<i class="fa fa-sticky-note-o"></i>
						<div class="col-md-6">
							<input 
								id="sivitas" type="text" class="form-control" 
								value="{{ $booking['sivitas'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="noWa" class="col-md-4 col-form-label text-md-left">{{ __('No. WA') }}</label>
						<i class="fa fa-sticky-note-o"></i>
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
						<i class="fa fa-sticky-note-o"></i>
						<div class="col-md-6">
							<input 
								id="namaAcara" type="text" class="form-control" 
								value="{{ $booking['nama_acara'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
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
						<i class="fa fa-calendar-o"></i>
						<div class="col-md-6">
							<input 
								id="waktuMulai" type="datetime-local" class="form-control" 
								value="{{ $booking['waktu_mulai'] }}" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="waktuSelesai" class="col-md-4 col-form-label text-md-left">{{ __('Waktu Selesai Webinar') }}</label>
						<i class="fa fa-calendar"></i>
						<div class="col-md-6">
							<input 
								id="waktuSelesai" type="datetime-local" class="form-control" 
								value="{{ $booking['waktu_akhir'] }}" onchange="onupdateWaktu()" disabled
							>
						</div>
					</div>

					<div class="form-group row">
						<label for="durasi" class="col-md-4 col-form-label text-md-left">{{ __('Durasi Webinar') }}</label>
						<i class="fa fa-clock-o"></i>
						<div class="col-md-6">
							<input id="durasi" type="text" class="form-control" value="" onchange="onupdateDurasi()" disabled> <div>jam</div>
						</div>
					</div>

					@if($isOwner || $isAdmin)
					<div class="form-group row">
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
						<input name="verify" type="hidden" value="setuju">

						<div class="form-group row">
							<label for="hostEmail" class="col-md-4 col-form-label text-md-left">{{ __('Host Account') }}</label>
							<i class="fa fa-envelope-o"></i>
							<div class="col-md-6">
								{{ isset($booking['api_host_email'])?'Last picked: '.$booking['api_host_email']:'' }}
								<select name="hostEmail" id="hostEmail" class="form-control">
									<option value="500 (1)">500 (1)</option>
									<option value="500 (2)">500 (2)</option>
									<option value="1000 (1)">1000 (1)</option>
								</select>
							</div>
						</div>
						<div class="form-group row mb-0">
							<div class="col-md-8 offset-md-4">
								<button type="submit" class="btn btn-submit">
									{{ __('Setujui Booking') }}
								</button>
								<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#denyModal" onclick="modalPopulate()">
									{{ __('Tolak Booking') }}
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
			<form action="{{route('booking.verify')}}" method="POST">
				@csrf
				<input name="id" type="hidden" value="{{ $booking['id'] }}">
				<input name="verify" type="hidden" value="tolak">
				<div class="modal-body">
					<div class="form-group row">
						<label for="alasan" class="col-md-4 col-form-label text-md-left">{{ __('Alasan') }}</label>
						<i class="fa fa-sticky-note-o"></i>
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

<script src="{{ asset('js/booking/durasi.js') }}" defer></script>
<script type="text/javascript" >
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

@endsection
