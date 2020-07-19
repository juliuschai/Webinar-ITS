@extends('layouts.app')

@section('content')

<div class="container">
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
	<table class="table">
		<thead class="thead-dark">
			<tr>
				<th scope="col">Nama</th>
				<th scope="col">Email</th>
				<th scope="col">Integra</th>
				<th scope="col">Sivitas</th>
				<th scope="col">Admin</th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
			@foreach ($users as $user)
			<tr>
				<td>{{ $user->nama }}</td>
				<td>{{ $user->email }}</td>
				<td>{{ $user->integra }}</td>
				<td>{{ $user->sivitas }}</td>
				<td>{{ $user->is_admin?'Admin':'User' }}</td>
				<td>
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

{{ $users->appends(compact('nama', 'email', 'integra'))->links() }}
@endsection
