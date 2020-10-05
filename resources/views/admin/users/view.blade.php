@extends('layouts.header')

@section('content')

<!-- <div class="container"> -->
<div class="right_col booking" role="main">
	<div class="col-md-12 col-sm-12">
		<h2 class="table-title">Daftar User</h2>

		@if(session()->has('message'))
		<div class="alert alert-success">
			{{ session()->get('message') }}
		</div>
		@endif

		<!-- Revoke admin button template -->
		<div id="revokeAdmBtnTemplate" style="display: none;">
			<form method="POST" action="{{route('admin.users.admin.revoke', ["id" => 0])}}" class="d-inline">
				@csrf
				<button style="padding: 3px 8px; font-size: 11pt;" type="submit" class="btn btn-info"
					title="Nonaktifkan user dari admin">
					<i class="fa fa-times"></i>
					Admin
				</button>
			</form>
		</div>
		<!-- Give admin button template -->
		<div id="giveAdmBtnTemplate" style="display: none;">
			<form method="POST" action="{{route('admin.users.admin.give', ['id' => 0])}}" class="d-inline">
				@csrf
				<button style="padding: 3px 8px; font-size: 11pt;" type="submit" class="btn btn-danger"
					title="Buat user menjadi admin">
					<i class="fa fa-check"></i>
					Admin
				</button>
			</form>
		</div>

		<!-- Revoke verifier button template -->
		<div id="revokeVerifierBtnTemplate" style="display: none;">
			<form method="POST" action="{{route('admin.users.verifier.revoke', ["id" => 0])}}" class="d-inline">
				@csrf
				<button style="padding: 3px 8px; font-size: 11pt;" type="submit" class="btn btn-info"
					title="Nonaktifkan user dari verifier">
					<i class="fa fa-times"></i>
					Verifier
				</button>
			</form>
		</div>
		<!-- Give verifier button template -->
		<div id="giveVerifierBtnTemplate" style="display: none;">
			<form method="POST" action="{{route('admin.users.verifier.give', ['id' => 0])}}" class="d-inline">
				@csrf
				<button style="padding: 3px 8px; font-size: 11pt;" type="submit" class="btn btn-danger"
					title="Buat user menjadi verifier, verifier menadapatkan notifikasi pada booking baru dan menjadi pilihan dropdown Admin DPTSI">
					<i class="fa fa-check"></i>
					Verifier
				</button>
			</form>
		</div>

		<table id="userTable" class="table table-bordered table-striped table-bordered table-hover"
			data-ajaxurl="{{route('admin.users.view.data')}}">
			<thead class="thead-custom-blue">
				<tr>
					<th class="text-center" scope="col">Id</th>
					<th class="text-center" scope="col">Nama</th>
					<th class="text-center" scope="col">Email</th>
					<th class="text-center" scope="col">Integra</th>
					<th class="text-center" scope="col">Sivitas</th>
					<th class="text-center" scope="col">Admin</th>
					<th class="text-center" scope="col">Verifier</th>
					<th class="text-center" scope="col">Aksi</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
			<tfoot class="thead-custom-blue">
				<tr>
					<th></th>
					<th><input type="text" placeholder="Search Nama"></th>
					<th><input type="text" placeholder="Search Email"></th>
					<th><input type="text" placeholder="Search Integra"></th>
					<th><input type="text" placeholder="Search Sivitas"></th>
					<th>
						<select id="admSelect">
							<option value>Semua</option>
							<option value="true">Admin</option>
							<option value="false">User</option>
						</select>
					</th>
					<th></th>
					<th></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

{{-- {{ $users->appends(compact('nama', 'email', 'integra'))->links() }} --}}
@endsection


@section('scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.css" defer />
<script src="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.js" defer></script>
<script src="{{asset('js/util/datatablesPlugin.js') }}" defer></script>
<script src="{{asset('js/users/view.js') }}" defer></script>
@endsection