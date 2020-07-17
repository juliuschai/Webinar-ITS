@extends('layouts.app')

@section('content')

<div class="container">
	@if(session()->has('message'))
	<div class="alert alert-success">
			{{ session()->get('message') }}
	</div>
	@endif
	<div class="form-group row">
		<div class="col">Unit baru</div>
	</div>
	<div class="form-group row col-md-12 col-sm-12">
		<form method="POST" action="{{route('organisasi.add')}}">
			@csrf
			<div class="form-group row">
				<label for="orgNama" class="col-md-4 col-form-label text-md-left">Nama:</label>
				<div class="col">
					<input id="orgNama" type="text" name="orgNama" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label for="orgType" class="col-md-4 col-form-label text-md-left">Type:</label>
				<div class="col">
					<select name="orgType" id="orgType" class="form-control">
						@foreach ($types as $type)
							<option value="{{$type->id}}">{{$type->nama}}</option>	
						@endforeach
					</select>
				</div>
			</div>
			<div class="col">
				<button type="submit" class="btn btn-submit">Add Unit</button>
			</div>
		</form>
	</div>
	<table class="table">
		<thead class="thead-dark">
			<tr>
				<th scope="col">Nama</th>
				<th scope="col">Type</th>
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
			@foreach ($organisasis as $organisasi)
			<tr>
				<td>{{ $organisasi->nama }}</td>
				<td>{{ $organisasi->org_type }}</td>
				<td><form method="POST" action="{{route('organisasi.delete', ["id" => $organisasi->id])}}">@csrf<button class="btn btn-danger">delete</button></form></td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>

{{ $organisasis->links() }}
@endsection
