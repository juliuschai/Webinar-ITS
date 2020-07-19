@extends('layouts.admin')

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
	<div class="form-group row">
		<div class="col">Search</div>
	</div>
	<div class="form-group row col-md-12 col-sm-12">
		<form method="GET" action="{{route('admin.users.view')}}">
			<div class="form-group row">
				<label for="nama" class="col-md-4 col-form-label text-md-left">Nama:</label>
				<div class="col">
					<input id="nama" type="text" name="nama" class="form-control" value="{{$nama}}">
				</div>
			</div>
			<div class="form-group row">
				<label for="email" class="col-md-4 col-form-label text-md-left">Email:</label>
				<div class="col">
					<input id="email" type="text" name="email" class="form-control" value="{{$email}}">
				</div>
			</div>
			<div class="form-group row">
				<label for="integra" class="col-md-4 col-form-label text-md-left">Integra:</label>
				<div class="col">
					<input id="integra" type="text" name="integra" class="form-control" value="{{$integra}}">
				</div>
			</div>
			<button class="btn btn-submit">Search</button>
		</form>
	</div>
	<table class="table table-bordered table-striped table-bordered table-hover">
		<thead class="thead-custom-blue">
			<tr>
				<th class="text-center" scope="col">Nama</th>
				<th class="text-center" scope="col">Email</th>
				<th class="text-center" scope="col">Integra</th>
				<th class="text-center" scope="col">Sivitas</th>
				<th class="text-center" scope="col">Admin</th>
				<th class="text-center" scope="col">Aksi</>
			</tr>
		</thead>
		<tbody>
			@foreach ($users as $user)
			<tr>
				<td>{{ $user->nama }}</td>
				<td class="text-center">{{ $user->email }}</td>
				<td class="text-center">{{ $user->integra }}</td>
				<td class="text-center">{{ $user->sivitas }}</td>
				<td class="text-center">{{ $user->is_admin?'Admin':'User' }}</td>
				<td class="text-center">
					@if($user->is_admin)
					<form method="POST" action="{{route('admin.users.revoke', ["id" => $user->id])}}">
						<button type="submit" class="btn btn-warning">Non-Aktifkan Admin</button>
					@else
					<form method="POST" action="{{route('admin.users.give', ['id' => $user->id])}}">
						<button type="submit" class="btn btn-danger">Aktifkan Admin</button>
					@endif
						@csrf
					</form>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	</div>
</div>

{{ $users->appends(compact('nama', 'email', 'integra'))->links() }}
@endsection
