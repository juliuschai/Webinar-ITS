@extends('layouts.header')

@section('content')
	<div class="right_col booking" role="main">
		<div class="col-md-12 col-sm-12">
			<h2 class="table-title">Daftar Webinar</h2>
            <!-- Tempaltes -->
            <!-- Disetujui templates -->
            <div id="disetujuiValue" style="display: none;">
				<div style="background-color: #FF6961; padding: 8px 8px; margin: 0px" class="text-center">
				</div>
			</div>
			<!-- Status templates -->
			<div id="ditolakStatus" style="display: none;">
				<div style="background-color: #FF6961; padding: 8px 8px; margin: 0px" class="text-center">
					Ditolak
				</div>
			</div>
			<div id="disetujuiStatus" style="display: none;">
				<div style="background-color: #99d18f; padding: 8px 8px; margin: 0px" class="text-center">
					Disetujui
				</div>
			</div>
			<div id="menungguStatus" style="display: none;">
				<div style="background-color: #FCF0CF; padding: 8px 8px; margin: 0px" class="text-center">
					Menunggu<br>Konfirmasi
				</div>
			</div>
			<!-- Action button templates -->
			<div id="viewBtnTemplate" style="display: none;">
				<a href="{{ route('booking.view', ['tipe_zoom'=>$tipe_zoom, 'id'=>0]) }}">
				<button style="padding: 3px 8px" type="button" class="btn btn-custom-primary" title="Detail Webinar">
					<i class="fa fa-search"></i>
				</button>
				</a>
			</div>
			<div id="editBtnTemplate" style="display: none;">
				<a href="{{ route('booking.edit', ['tipe_zoom'=>$tipe_zoom, 'id'=>0]) }}">
				<button id="editBtn" style="padding: 3px 8px" type="button" class="btn btn-custom-warning" title="Edit Webinar">
					<i class="fa fa-pencil"></i>
				</button>
				</a>
			</div>
			<div id="delBtnTemplate" style="display: none;">
				<form action="{{ route('booking.delete',['tipe_zoom'=>$tipe_zoom, 'id'=>0]) }}" method="post" class="d-inline">
					@csrf
					<button style="padding: 3px 8px;" type="submit" class="btn btn-custom-danger" onclick="return confirm('Apakah anda yakin untuk menghapus Webinar?')" title="Hapus Webinar">
						<i class="fa fa-trash-o"></i>
					</button>
				</form>
			</div>
			<!-- Disable Button -->
			<div id="editBtnDisable" style="display: none;">
				<a href="{{ route('booking.edit', ['tipe_zoom'=>$tipe_zoom, 'id'=>0]) }}">
				<button disabled style="padding: 3px 8px" type="button" class="btn btn-custom-warning" title="Edit Webinar">
					<i class="fa fa-pencil"></i>
				</button>
				</a>
			</div>
			<div id="delBtnDisable" style="display: none;">
				<form action="{{ route('booking.delete',['tipe_zoom'=>$tipe_zoom, 'id'=>0]) }}" method="post" class="d-inline">
					@csrf
					<button disabled style="padding: 3px 8px;" type="submit" class="btn btn-custom-danger" onclick="return confirm('Apakah anda yakin untuk menghapus Webinar?')" title="Hapus Webinar">
						<i class="fa fa-trash-o"></i>
					</button>
				</form>
			</div>
            <small>Timezone Asia/Jakarta (GMT+7)</small>
			<table
				id="tableElm"
				class="table table-bordered table-striped table-bordered table-hover"
				data-ajaxurl="{{ route('list.data', ['tipe_zoom'=>$tipe_zoom]) }}"
			>
				<thead class="thead-custom-blue">
					<tr>
					<th class="text-center" scope="col">Id</th>
					<th class="text-center" scope="col">Tanggal Booking</th>
					<th class="text-center" scope="col">Tanggal Webinar</th>
					<th class="text-center" scope="col">Waktu</th>
					<th class="text-center" scope="col">Nama Acara</th>
					<th class="text-center" scope="col">Penyelenggara Acara</th>
					<th class="text-center" scope="col">Admin DPTSI</th>
					<th class="text-center" scope="col">Status</th>
					<th class="text-center" scope="col">Aksi</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
				<tfoot class="thead-custom-blue">
					<tr>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th><input type="text" placeholder="Search Nama Acara"></th>
						<th><input type="text" placeholder="Search Penyelenggara Acara"></th>
						<th><input type="text" placeholder="Search Admin"></th>
						<th>
							<select id="searchStatus">
								<option value="">Semua</option>
								<option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
								<option value="Ditolak">Ditolak</option>
								<option value="Disetujui">Disetujui</option>
							</select>
						</th>
						<th></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
<!-- </div> -->
@endsection

@section('scripts')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.css" defer/>
<script src="https://cdn.datatables.net/v/dt/dt-1.10.21/datatables.min.js" defer></script>
<script src="{{asset('js/util/datatablesPlugin.js') }}" defer></script>
<script src="{{asset('js/booking/table/view.js') }}?4" defer></script>
@endsection
