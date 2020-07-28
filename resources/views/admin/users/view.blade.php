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
		<form method="POST" action="{{route('admin.users.revoke', ["id" => 0])}}">
			@csrf
			<button style="padding: 3px 8px; font-size: 11pt;" type="submit" class="btn btn-info">
			<i class="fa fa-times"></i>
				Non-Aktifkan Admin
			</button>
		</form>
	</div>
	<!-- Give admin button template -->
	<div id="giveAdmBtnTemplate" style="display: none;">
		<form method="POST" action="{{route('admin.users.give', ['id' => 0])}}">
			@csrf
			<button type="submit" class="btn btn-danger">Aktifkan Admin</button>
		</form>
	</div>

	<table 
		id="userTable" 
		class="table table-bordered table-striped table-bordered table-hover"
		data-ajaxurl="{{route('admin.users.view.data')}}"
		data-length="{{$length}}"
	>
		<thead class="thead-custom-blue">
			<tr>
				<th class="text-center" scope="col">Id</th>
				<th class="text-center" scope="col">Nama</th>
				<th class="text-center" scope="col">Email</th>
				<th class="text-center" scope="col">Integra</th>
				<th class="text-center" scope="col">Sivitas</th>
				<th class="text-center" scope="col">Admin</th>
				<th class="text-center" scope="col">Aksi</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($users as $user)
			<tr>
				<td>{{ $user->id }}</td>
				<td>{{ $user->nama }}</td>
				<td class="text-center">{{ $user->email }}</td>
				<td class="text-center">{{ $user->integra }}</td>
				<td class="text-center">{{ $user->sivitas }}</td>
				<td class="text-center">{{ $user->is_admin }}</td>
				<td class="text-center"></td>
			</tr>
			@endforeach
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
			</tr>
		</tfoot>
	</table>
	</div>
</div>

{{-- {{ $users->appends(compact('nama', 'email', 'integra'))->links() }} --}}
@endsection


@section('scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.css" defer/>
<script src="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.js" defer></script>
<script src="{{asset('js/util/datatablesPlugin.js') }}" defer></script>
<script src="{{asset('js/users/view.js') }}" defer></script>
@endsection
