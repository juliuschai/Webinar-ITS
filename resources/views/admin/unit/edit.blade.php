@extends('layouts.admin')

@section('content')

<div class="right_col booking" role="main">
	<div class="col-md-12 col-sm-12">
	<div class="form-group row col-md-12 col-sm-12">
		<form method="POST" action="{{route('admin.unit.edit', ['id' => $unit->id])}}">
			@csrf
			<div class="form-group row">
				<label for="unitNama" class="col-md-4 col-form-label text-md-left">Nama:</label>
				<div class="col">
					<input id="unitNama" type="text" name="unitNama" class="form-control" value="{{$unit->nama}}">
				</div>
			</div>
			<div class="form-group row">
				<label for="unitType" class="col-md-4 col-form-label text-md-left">Type:</label>
				<div class="col">
					<select name="unitType" id="unitType" class="form-control">
						@foreach ($types as $type)
							<option value="{{$type->id}}" {{ $unit->unit_type_id == $type->id ? 'selected':''}}>{{$type->nama}}</option>	
						@endforeach
					</select>
				</div>
			</div>
			<div class="col">
				<button type="submit" class="btn btn-submit">Edit Unit</button>
			</div>
		</form>
	</div>
</div>
</div>
@endsection