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
		<form method="POST" action="{{route('unit.add')}}">
			@csrf
			<div class="form-group row">
				<label for="unitNama" class="col-md-4 col-form-label text-md-left">Nama:</label>
				<div class="col">
					<input id="unitNama" type="text" name="unitNama" class="form-control">
				</div>
			</div>
			<div class="form-group row">
				<label for="unitType" class="col-md-4 col-form-label text-md-left">Type:</label>
				<div class="col">
					<select name="unitType" id="unitType" class="form-control">
						@foreach ($types as $type)
							<option value="{{$type->id}}" {{$type->id == 3?'selected':''}}>{{$type->nama}}</option>	
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
				<th scope="col"></th>
			</tr>
		</thead>
		<tbody>
			@foreach ($units as $unit)
			<tr>
				<td>{{ $unit->nama }}</td>
				<td>{{ $unit->unit_type }}</td>
				<td><a href="{{route('unit.edit', ['id'=>$unit->id])}}"><button class="btn btn-info">Edit</button></a></td>
				<td>
					<form method="POST" action="{{route('unit.delete', ["id" => $unit->id])}}">
						@csrf
						<button class="btn btn-danger">Delete</button>
					</form>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>

{{ $units->links() }}
@endsection
