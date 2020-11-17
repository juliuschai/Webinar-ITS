@extends('layouts.header')

@section('content')

<div class="right_col booking" role="main">
	<div class="col-md-12 col-sm-12">
	<h2 class="table-title">Daftar Host</h2>
	@if(session()->has('message'))
	<div class="alert alert-success">
			{{ session()->get('message') }}
	</div>
	@endif

	<table id="hostTable"
		class="table table-bordered table-striped table-bordered table-hover dataTable"
		data-ajaxurl="{{ route('admin.host.data') }}"
		data-length="{{ $length }}"
	>
		<thead class="thead-custom-blue">
			<tr>
				<th scope="col">Id</th>
				<th scope="col">Nama</th>
				<th scope="col">Zoom ID</th>
				<th scope="col">Zoom Password</th>
				<th scope="col">Password</th>
                <th scope="col">Kuota</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($hosts as $host)
			<tr>
				<td>{{ $host->id }}</td>
				<td>{{ $host->nama }}</td>
				<td>{{ $host->zoom_id }}</td>
                <td>{{ $host->zoom_email }}</td>
                <td>{{ $host->pass }}</td>
                <td>{{ $host->max_peserta }}</td>
			</tr>
			@endforeach
		</tbody>
	</table>
	</div>
</div>

@endsection

@section('scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.css" defer/>
<script src="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.js" defer></script>
<script src="{{asset('js/util/datatablesPlugin.js') }}" defer></script>
<script src="{{asset('js/host/view.js') }}?4" defer></script>
@endsection
