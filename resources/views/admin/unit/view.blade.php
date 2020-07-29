@extends('layouts.header')

@section('content')

<div class="right_col booking" role="main">
	<div class="col-md-12 col-sm-12">
	<h2 class="table-title">Daftar Unit</h2>
	@if(session()->has('message'))
	<div class="alert alert-success">
			{{ session()->get('message') }}
	</div>
	@endif
	<!-- Edit button template -->
	<div id="editBtnTemplate" style="display: none;">
		<a href="{{route('admin.unit.edit', ['id'=>0])}}">
			<button style="padding: 3px 8px; font-size: 11pt;" class="btn btn-custom-warning">
			<i class="fa fa-pencil-square-o"></i> Edit
			</button>
		</a>
	</div>

	<!-- Delete button template -->
	<div id="deleteBtnTemplate" style="display: none;">
		<form method="POST" action="{{route('admin.unit.delete', ["id" => 0])}}">
			@csrf
			<button style="padding: 3px 8px; font-size: 11pt;" class="btn btn-custom-danger">
				<i class="fa fa-trash-o"></i> Delete
			</button>
		</form>
	</div>
	<table id="unitTable" 
		class="table table-bordered table-striped table-bordered table-hover dataTable" 
		data-ajaxurl="{{route('admin.unit.view.data')}}"
		data-length="{{ $length }}"
		data-types="{{json_encode($types)}}"
	>
		<thead class="thead-custom-blue">
			<tr>
				<th scope="col">Id</th>
				<th scope="col">Nama</th>
				<th scope="col">Type</th>
				<th scope="col">Edit</th>
				<th scope="col">Delete</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($units as $unit)
			<tr>
				<td>{{ $unit->id }}</td>
				<td>{{ $unit->nama }}</td>
				<td>{{ $unit->unit_type }}</td>
				<td>
				</td>
				<td>
				</td>
			</tr>
			@endforeach
		</tbody>
		<tfoot>
			<tr>
				<th></th>
				<th><input type="text" placeholder="Search Nama"></th>
				<th>
					<select id="searchTypeSelect">
						<option value="">Semua</option>
						<!-- The rest of the options are generated by js -->
					</select>
				</th>
				<th></th>
				<th></th>
			</tr>
	</tfoot>
</table>
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
@endsection

@section('scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.css" defer/>
<script src="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.js" defer></script>
<script src="{{asset('js/util/datatablesPlugin.js') }}" defer></script>
<script src="{{asset('js/unit/view.js') }}" defer></script>
@endsection
