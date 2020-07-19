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
						<!-- multistep form -->
						<form id="msform">
						<!-- progressbar -->
						<ul id="progressbar">
							<li class="active">Account Setup</li>
							<li>Social Profiles</li>
							<li>Personal Details</li>
						</ul>
						<!-- fieldsets -->
						<fieldset>
							<h2 class="fs-title">Create your account</h2>
							<h3 class="fs-subtitle">This is step 1</h3>
							<input type="text" name="email" placeholder="Email" />
							<input type="password" name="pass" placeholder="Password" />
							<input type="password" name="cpass" placeholder="Confirm Password" />
							<input type="button" name="next" class="next action-button" value="Next" />
						</fieldset>
						<fieldset>
							<h2 class="fs-title">Social Profiles</h2>
							<h3 class="fs-subtitle">Your presence on the social network</h3>
							<input type="text" name="twitter" placeholder="Twitter" />
							<input type="text" name="facebook" placeholder="Facebook" />
							<input type="text" name="gplus" placeholder="Google Plus" />
							<input type="button" name="previous" class="previous action-button" value="Previous" />
							<input type="button" name="next" class="next action-button" value="Next" />
						</fieldset>
						<fieldset>
							<h2 class="fs-title">Personal Details</h2>
							<h3 class="fs-subtitle">We will never sell it</h3>
							<input type="text" name="fname" placeholder="First Name" />
							<input type="text" name="lname" placeholder="Last Name" />
							<input type="text" name="phone" placeholder="Phone" />
							<textarea name="address" placeholder="Address"></textarea>
							<input type="button" name="previous" class="previous action-button" value="Previous" />
							<input type="submit" name="submit" class="submit action-button" value="Submit" />
						</fieldset>
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
