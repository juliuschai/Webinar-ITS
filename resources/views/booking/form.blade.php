@extends('layouts.header')

@section('content')

	<div class="right_col booking" role="main">
		<div class="col-md-12 col-sm-12">
			<div class="card">
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

                    <small>Timezone Asia/Jakarta (GMT+7)</small>
					@if(Route::is('booking.new'))
					<form id="bookingForm" method="POST" action="{{ route('booking.new', ['tipe_zoom'=>$tipe_zoom]) }}" enctype="multipart/form-data">
					@elseif(Route::is('booking.edit'))
					<form id="bookingForm" method="POST" action="{{ route('booking.edit', ['tipe_zoom'=>$tipe_zoom, 'id' => $booking->id]) }}" enctype="multipart/form-data">
					@endif
						@csrf
						<div id="one" class="tab">

							<ul id="section-tabs">
								<li style="margin-left: -20px;" id="ho" class="current active">Data PIC Zoom</li>
								<li style="margin-right: -20px;"id="hi">Informasi</li>
							</ul>

							<div>Jika data yang terlampir belum sesuai dengan data myITS SSO, silahkan login ulang dan coba lagi</div>
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
										id="noWa" type="text" class="form-control"
										value="{{ old('noWa')??$booking['no_wa'] }}" disabled
									>
								</div>
								<div class="col-md-6">
									<sub class="">Jika No.HP/WA tidak sesuai, dimohon untuk melakukan perubahan di Menu Settings myITS SSO</sub>
								</div>
							</div>
							<div class="form-group row">
							<button style="position: absolute;bottom: 15px;right: 50px;" type="button" id="nextBtn" class="btn btn-submit next-btn" onclick="nextPrev(1)">Next</button>
							</div>
						</div>

						<div id="two" class="tab">
						<ul id="section-tabs">
							<li style="margin-left: -20px;" id="ho">Data PIC Zoom</li>
							<li style="margin-right: -20px;"id="hi" class="current active">Informasi</li>
						</ul>

							<div class="form-group row">
								<label for="kategoriAcara" class="col-md-4 col-form-label text-md-left">{{ __('Kategori Acara') }}<p style="color: red" class="d-inline">*</p></label>
									<i class="fa fa-list-alt booking"></i>
									<div class="col-md-6">
										<select name="kategoriAcara" class="form-control">
											@foreach ($kategoris as $kategori)
												<option value="{{$kategori->id}}" {{old('kategoriAcara')??$booking->kategori_id == $kategori->id?'selected':''}}>{{$kategori->nama}}</option>
											@endforeach
										</select>
									</div>
							</div>

							<div class="form-group row">
								<label for="namaAcara" class="col-md-4 col-form-label text-md-left">{{ __('Nama Acara') }}<p style="color: red" class="d-inline">*</p></label>
								<i class="fa fa-sticky-note-o booking"></i>
								<div class="col-md-6">
									<textarea style="resize: none;" rows="2" cols="30"
										name="namaAcara" id="namaAcara" type="text" class="form-control"
										required>{{ old('namaAcara')??$booking['nama_acara'] }}</textarea>
								</div>
							</div>

							<div class="form-group row">
								<label for="penyelengaraAcara" class="col-md-4 col-form-label text-md-left">{{ __('Penyelengara Acara') }}<p style="color: red" class="d-inline">*</p></label>
								<i class="fa fa-user-circle booking"></i>
								<div class="col-md-6">
									<input id="unitDatas" hidden
										data-types="{{json_encode($unitTypes)}}"
										data-units="{{json_encode($units)}}"
										data-curtypeid="{{old('penyelengaraAcaraTypes')??$booking['unit_type_id']}}"
										data-curunitid="{{old('penyelengaraAcara')??$booking['unit_id']}}"
									>
									<select name="penyelengaraAcaraTypes" id="penyelengaraAcaraTypes" class="form-control">
									</select>
									<br>
									<select name="penyelengaraAcara" id="penyelengaraAcara" class="form-control">
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label for="dokumenPendukung" class="col-md-4 col-form-label text-md-left">{{ __('File Pendukung') }}
									@if(!$booking->file_pendukung)
									<p style="color: red" class="d-inline">*</p>
									@endif
								</label>
								<i class="fa fa-file booking"></i>
								<div class="col-md-6">
									<input style="border: none; margin-left: -12px;" id="dokumenPendukung" type="file" name="dokumenPendukung" class="form-control">
								</div>
								<div class="col-md-6">
									<sub class="">Max size: 2 mb, format pdf,png,jpg,jpeg</sub>
									@if($booking->file_pendukung)
									<br><sub class="">File pendukung tidak perlu di upload ulang, kecuali jika file pendukung ingin diganti</sub>
									@endif
									</sub>
								</div>
							</div>

							@if(isset($booking->file_pendukung))
							<div class="form-group row filePendukungExists">
								<label class="col-md-4 col-form-label text-md-left"></label>
								<i class="fa fa-user-circle booking"></i>
								<div class="col-md-6">
									<a href="{{route('dokumen.get', ['id' => $booking->id])}}" target="_blank"><button type="button">View</button></a>
									<a href="{{route('dokumen.download', ['id' => $booking->id])}}" target="_blank"><button type="button">Download</button></a>
								</div>
							</div>
							@endif

							<sub>Keterangan :</sub></br>
							<sub>- Untuk {{$tipe_zoom}} lebih dari 1 sesi (1 hari) silahkan membaut sesi baru</sub></br>
							<sub>- Untuk keperluan gladi bersih silahkan membuat sesi baru</sub></br>
							<sub>- Gunakan timezone (GMT+7)</sub>

							<div class="bookingGladiTimesForms">
							</div>
							<div class="form-group row">
								<div class="col-md-4"></div>
								<div class="col-md-6">
									<button type="button" class="btn btn-info" style="padding: 5px;font-size:12px;" onclick="addGladiTimesForm()">Tambah Sesi Gladi</button>
									<button type="button" class="btn btn-info" style="padding: 5px;font-size:12px;" onclick="addTimesForm()">Tambah Sesi</button>
								</div>
							</div>

							<div class="bookingTimesForms" data-datas="{{json_encode(old('bookingTimes')??$booking_times)}}">
								<div class="bookingTimesForm">
								<hr style="width:100%;text-align:left;margin-top:5px;margin-bottom:10px">
									<input type="hidden" name="bookingTimes[0][id]" class="id">
									<input type="hidden" name="bookingTimes[0][gladi]" class="gladi" value="false">
									<h2 class="sesiTitle">Sesi {{$tipe_zoom}} 1</h2>

									<div class="form-group row">
										<label class="col-md-4 col-form-label text-md-left">{{ __('Waktu ').$tipe_zoom }}<p style="color: red" class="d-inline">*</p></label>
										<i class="fa fa-calendar-o booking"></i>
										<div class="col-md-6">
											<input type="date" class="mulaiDate" onclick="updateWaktu(this)">
											<select class="mulaiTime" onclick="updateWaktu(this)"></select>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-md-4 col-form-label text-md-left">{{ __('Durasi ').$tipe_zoom }}<p style="color: red" class="d-inline">*</p></label>
										<i class="fa fa-clock-o booking"></i>
										<div class="col-md-6">
											<select class="durHour" onclick="updateWaktu(this)"></select> jam
											<select class="durMinute" onclick="updateWaktu(this)"></select> menit
										</div>
									</div>
									<input class="waktuMulai" type="hidden" name="bookingTimes[0][waktuMulai]" required>
									<input class="waktuSelesai" type="hidden" name="bookingTimes[0][waktuSelesai]" required>
									<div class="form-group row">
										<label class="col-md-4 col-form-label text-md-left">{{ __('Peserta sebanyak 500 atau lebih') }}<p style="color: red" class="d-inline">*</p></label>
										<div class="col-md-6">
											<input
												id="500" type="radio" class="form-radio maxPeserta" name="bookingTimes[0][maxPeserta]"
												value="500"
											><div class="form-option">&le; 500</div>
											<input
												id="1000" type="radio" class="form-radio maxPeserta" name="bookingTimes[0][maxPeserta]"
												value="1000"
											><div class="form-option">501 - 1000</div>
										</div>
									</div>

									<div class="form-group row">
										<label class="col-md-4 col-form-label text-md-left">{{ __('Layanan Live Youtube ITS') }}</label>
										<div class="col-md-6">
											<input
												type="radio" class="form-radio relayITSTV" name="bookingTimes[0][relayITSTV]"
												value="true"
											><div class="form-option">Iya</div>
											<input
												type="radio" class="form-radio relayITSTV" name="bookingTimes[0][relayITSTV]"
												value="false"
											><div class="form-option">Tidak</div>
										</div>
										<div class="col-md-6">
											<sub class="">Silahkan menghubungi Unit Komunikasi Publik (UKP) pada <a href="https://servicedesk.its.ac.id/" target="_blank">
											<br>servicedesk.its.ac.id</a> untuk permohonan Layanan Live Youtube ITS.</sub>
										</div>
									</div>
									<div class="d-flex">
										<button type="button" class="btn btn-custom-danger" onclick="deleteField(this)">Hapus Sesi</button>
									</div>
								</div>
							</div>

							<!-- </div> -->
							<div class="form-group row mb-0">
								<div class="col-md-8 offset-md-4">
									<button style="position: absolute;bottom: 0px;right: 200px;" type="button" id="prevvBtn" class="btn btn-submit next-btn" onclick="nextPrev(-1)">Previous</button>
									<button style="position: absolute;bottom: 0px;right: 50px;" type="button" id="submitBtn" class="btn btn-submit" onclick="submitForm()">
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
@endsection

@section('scripts')
@if($isAdmin)
<script src="{{ asset('js/booking/adminform.js') }}?4" defer></script>
@else
<script src="{{ asset('js/booking/form.js') }}?4" defer></script>
@endif
<script src="{{ asset('js/booking/units.js') }}" defer></script>
<script src="{{ asset('js/booking/tabControls.js') }}" defer></script>
@endsection
