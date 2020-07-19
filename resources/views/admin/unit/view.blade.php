@extends('layouts.admin')

@section('content')

<div class="right_col booking" role="main">
	<div class="col-md-12 col-sm-12">
	<h2 class="table-title">Daftar Unit</h2>
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
	<table class="table table-bordered table-striped table-bordered table-hover">
		<thead class="thead-custom-blue">
			<tr>
				<th class="text-center" scope="col">Nama</th>
				<th class="text-center" scope="col">Type</th>
				<th class="text-center" scope="col">Edit</th>
				<th class="text-center" scope="col">Delete</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($units as $unit)
			<tr>
				<td class="text-center">{{ $unit->nama }}</td>
				<td class="text-center">{{ $unit->unit_type }}</td>
				<td class="text-center"><a href="{{route('admin.unit.edit', ['id'=>$unit->id])}}"><button class="btn btn-info">Edit</button></a></td>
				<td class="text-center">
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
