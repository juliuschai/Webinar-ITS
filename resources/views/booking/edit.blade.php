@extends('layouts.user')

@section('content')

<!-- <div class="container"> -->
	<!-- <div class="row justify-content-center"> -->
	<div class="right_col booking" role="main">
		<div class="col-md-12 col-sm-12">
			<div class="card">
				<div class="card-header">{{ __('Edit Form') }}</div>

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
					<form id="bookinForm" method="POST" action="{{ route('booking.new') }}">
					@elseif(Route::is('booking.edit'))
					<form id="bookinForm" method="POST" action="{{ route('booking.edit') }}">
						<input name="id" type="hidden" value="{{ $id }}">
					@endif
						@csrf
						<form id="regForm" action="">

<h1>Register:</h1>

<!-- One "tab" for each step in the form: -->
<div class="tab">Name:
  <p><input placeholder="First name..." oninput="this.className = ''"></p>
  <p><input placeholder="Last name..." oninput="this.className = ''"></p>
</div>

<div class="tab">Contact Info:
  <p><input placeholder="E-mail..." oninput="this.className = ''"></p>
  <p><input placeholder="Phone..." oninput="this.className = ''"></p>
</div>

<div class="tab">Birthday:
  <p><input placeholder="dd" oninput="this.className = ''"></p>
  <p><input placeholder="mm" oninput="this.className = ''"></p>
  <p><input placeholder="yyyy" oninput="this.className = ''"></p>
</div>

<div class="tab">Login Info:
  <p><input placeholder="Username..." oninput="this.className = ''"></p>
  <p><input placeholder="Password..." oninput="this.className = ''"></p>
</div>

<div style="overflow:auto;">
  <div style="float:right;">
    <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
    <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
  </div>
</div>

<!-- Circles which indicates the steps of the form: -->
<div style="text-align:center;margin-top:40px;">
  <span class="step"></span>
  <span class="step"></span>
  <span class="step"></span>
  <span class="step"></span>
</div>

</form>
					</form>
				</div>
			</div>
		</div>
	</div>
<!-- </div> -->
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

<!-- Tambahkan -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
@endsection
