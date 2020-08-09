<table>
	<thead>
		<tr>
			<td>Nama PIC</td>
			<td>Email PIC</td>
			<td>Integra PIC</td>
			<td>No.Wa PIC</td>
			<td>Group PIC</td>
			<td>Kategori Webinar</td>
			<td>Nama Acara</td>
			<td>Unit</td>
			<td>Waktu Mulai Acara</td>
			<td>Waktu Akhir Acara</td>
			<td>Booking Disetujui</td>
			<td>Alasan</td>
			<td>Waktu Mulai Sesi</td>
			<td>Waktu Akhir Sesi</td>
			<td>Relay ITSTV</td>
			<td>Sesi Gladi</td>
			<td>Akun Webinar Zoom</td>
			<td>Webinar ID Zoom</td>
			<td>Nama File Pendukung</td>
		</tr>
	</thead>
	<tbody>
		@foreach ($datas as $data)
		<tr>
			<td>{{$data->user_nama}}</td>
			<td>{{$data->user_email}}</td>
			<td>{{$data->user_integra}}</td>
			<td>{{$data->user_no_wa}}</td>
			<td>{{$data->user_group_nama}}</td>
			<td>{{$data->b_kategori}}</td>
			<td>{{$data->b_nama_acara}}</td>
			<td>{{$data->b_unit_nama}}</td>
			<td>{{$data->b_waktu_mulai}}</td>
			<td>{{$data->b_waktu_akhir}}</td>
			<td>{{$data->b_disetujui}}</td>
			<td>{{$data->b_deskripsi_disetujui}}</td>
			<td>{{$data->bt_waktu_mulai}}</td>
			<td>{{$data->bt_waktu_akhir}}</td>
			<td>{{$data->bt_relay_ITSTV}}</td>
			<td>{{$data->bt_gladi}}</td>
			<td>{{$data->bt_host_nama}}</td>
			<td>{{$data->bt_webinar_id}}</td>
			<td>{{$data->b_nama_file}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
		