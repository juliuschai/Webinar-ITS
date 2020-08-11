@extends('layouts.header')

@section('content')
<div class="right_col booking" role="main">
	<div class="col-md-12 col-sm-12">
		<div class="card-header">{{ __('Export') }}</div>
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

			<input id="formRoutes" type="hidden" 
				data-bookingRoute="{{route('export.booking')}}" 
				data-filesRoute="{{route('export.files')}}"
			>

			<form id="formSubmit" action="" method="POST">
				@csrf
				<div class="form-group row">
					<label for="semuaWaktu" class="col-md-4">{{ __('Semua waktu') }}</label>
					<div class="col-md-6"><input id="semuaWaktu" type="checkbox" name="semuaWaktu" value="selected" onchange="disableWaktu()" ></div>
				</div>

				<div class="form-group row">
					<label for="bulanMulai" class="col-md-4">{{ __('Dari Bulan') }}</label>
					<div class="col-md-6"><input id="bulanMulai" type="month" class="form-control" onchange="updateWaktu()"></div>
				</div>

				<div class="form-group row">
					<label for="bulanAkhir" class="col-md-4">{{ __('Sampai Bulan') }}</label>
					<div class="col-md-6"><input id="bulanAkhir" type="month" class="form-control" onchange="updateWaktu()"></div>
				</div>

				<input id="waktuMulai" name="waktuMulai" type="hidden">
				<input id="waktuAkhir" name="waktuAkhir" type="hidden">

				<div class="form-group row">
					<label for="kategori" class="col-md-4">{{ __('Kategori') }}</label>
					<div class="col-md-6">
						<select name="kategori" id="kategori" class="form-control">
							<option value="">Semua</option>
							@foreach ($kategoris as $kategori)
							<option value="{{$kategori->id}}">{{$kategori->nama}}</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="form-group row">
					<label for="status" class="col-md-4">{{ __('Status') }}</label>
					<div class="col-md-6">
						<select name="status" id="status" class="form-control">
							<option value="">Semua</option>
							<option value="true">Disetujui</option>
							<option value="false">Ditolak</option>
							<option value="null">Menunggu Persetujuan</option>
						</select>
					</div>
				</div>

				<button type="button" class="btn btn-submit" onclick="submitDownloadBooking()">Download Booking</button>
				<button type="button" class="btn btn-submit" onclick="submitDownloadFiles()">Download File Pendukungs</button>
			</form>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="{{asset('js/export/export.js') }}?2" defer></script>
@endsection