@extends('layouts.app')

@section('content')

<div class="container">
	@if(session()->has('message'))
	<div class="alert alert-success">
			{{ session()->get('message') }}
	</div>
	@endif
	<div class="form-group row col-md-12 col-sm-12">
		<form id="submitForm" action="{{route('admin.unit.view')}}" method="GET">
			<div class="form-group row">
				<label for="unitNama" class="col-md-4 col-form-label text-md-left">Nama:</label>
				<div class="col">
					<input id="unitNama" type="text" name="unitNama" class="form-control" value="{{$unit_nama}}">
				</div>
			</div>
			<div class="form-group row">
				<label for="unitType" class="col-md-4 col-form-label text-md-left">Type:</label>
				<div class="col">
					<select name="unitType" id="unitType" class="form-control">
						<option value="" selected>Semua</option>	
						@foreach ($types as $type)
							<option value="{{$type->id}}" {{isset($type_id)?($type->id == $type_id?'selected':''):''}}>{{$type->nama}}</option>	
						@endforeach
					</select>
				</div>
			</div>
			<div class="col">
				<button class="btn btn-success" onclick="search()">Search</button>
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#confirmModal" onclick="modalPopulate()">
					Tambah Unit
				</button>
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
				<td><a href="{{route('admin.unit.edit', ['id'=>$unit->id])}}"><button class="btn btn-info">Edit</button></a></td>
				<td>
					<form method="POST" action="{{route('admin.unit.delete', ["id" => $unit->id])}}">
						@csrf
						<button class="btn btn-danger">Delete</button>
					</form>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p>Some text in the Modal..</p>
  </div>

</div>

<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmModalLabel">Konfirmasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="confirmationText" class="modal-body">
			</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<form id="modalSubmitForm" action="{{route('admin.unit.add')}}" method="POST">
					@csrf
					<input id="modalUnitNama" type="hidden" name="unitNama" value="">
					<input id="modalUnitType" type="hidden" name="unitType" value="">
					<button type="submit" class="btn btn-primary">Konfirmasi</button>
				</form>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript" >
	function modalPopulate() {
		let unitNama = document.getElementById('unitNama').value;
		let unitTypeSelElm = document.getElementById('unitType');
		let unitType = unitTypeSelElm.options[unitTypeSelElm.selectedIndex].innerText;
		if (unitTypeSelElm.selectedIndex == 0) {
			alert("Mohon pilih tipe unit");
			return;
		}
		let text = `Tambahkan ${unitNama} kategori ${unitType} ke database?`;
		document.getElementById('confirmationText').innerText = text;
		
		document.getElementById('modalUnitNama').value = unitNama;
		document.getElementById('modalUnitType').value = unitTypeSelElm.value;

	}

</script>

{{ $units->appends(compact('unit_nama', 'type_id'))->links() }}
@endsection
