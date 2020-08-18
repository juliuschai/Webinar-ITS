var bookingTableElm = $('#bookingTable');
// Setup - add a text input to each footer cell
var types = bookingTableElm.data('types');
$.each(types, function () {
	$('#searchTypeSelect')
		.append($("<option></option>")
			.val(this.id)
			.text(this.nama));
});

function pz(str) {
	return ("0" + str).slice(-2);
}

// DataTable
var ditolakStatus = $('#ditolakStatus');
var disetujuiStatus = $('#disetujuiStatus');
var menungguStatus = $('#menungguStatus');

var viewBtn = $('#viewBtnTemplate');
var editBtn = $('#editBtnTemplate');
var delBtn = $('#delBtnTemplate');

var disableEdit = $('#editBtnDisable');
var disableDel = $('#delBtnDisable');

bookingTableElm.DataTable({
	processing: true,
	serverSide: true,
	ajax: bookingTableElm.data('ajaxurl'),
	deferLoading: bookingTableElm.data("length"),
	columnDefs: [
		{
			"targets": 0,
			"data": "id",
			"name": 'id',
			"searchable": false,
			"visible": false,
		},
		{
			"targets": 1,
			"title": "Tanggal Booking",
			"data": "created_at",
			"name": "created_at",
			"searchable": false,
			"visible": true,
			"render": function (data, type, full, meta) {
				let date = new Date(data);
				return `${pz(date.getDate())}-${pz(date.getMonth() + 1)}-${date.getFullYear()}`
			},
		},
		{
			"targets": 2,
			"title": "Tanggal Webinar",
			"data": "waktu_mulai",
			"name": "waktu_mulai",
			"searchable": false,
			"visible": true,
			"render": function (data, type, full, meta) {
				let date = new Date(data);
				return `${pz(date.getDate())}-${pz(date.getMonth() + 1)}-${date.getFullYear()}`
			},
		},
		{
			"targets": 3,
			"title": "Waktu",
			"data": "waktu_mulai",
			"name": "waktu_mulai",
			"searchable": false,
			"visible": true,
			"render": function (data, type, full, meta) {
				let date = new Date(data);
				return `${pz(date.getHours())}:${pz(date.getMinutes())}:${pz(date.getSeconds())}`
			},
		},
		{
			"targets": 4,
			"title": "Nama Acara",
			"data": "nama_acara",
			"name": "nama_acara",
			"searchable": true,
			"visible": true,
		},
		{
			"targets": 5,
			"title": "Penyelenggara Acara",
			"data": "nama",
			"name": "nama",
			"searchable": true,
			"visible": true,
		},
		{
			"targets": 6,
			"title": "Admin DPTSI",
			"data": "",
			"name": "admin_dptsi_nama",
			"searchable": true,
			"visible": true,
			"render": function (data, type, full, meta) {
				if (data) {
					return data;
				} else if (full.admin_dptsi_nama && full.admin_dptsi_no_wa) {
					return `${full.admin_dptsi_nama} - ${full.admin_dptsi_no_wa}`
				} else {
					return " - ";
				}
			},
		},
		{
			"targets": 7,
			"title": "Status",
			"data": "disetujui",
			"name": "disetujui",
			"searchable": true,
			"visible": true,
			"render": function (data, type, full, meta) {
				if (data === null || data === "") {
					return menungguStatus.clone().show().html();
				} else if (data == true) {
					return disetujuiStatus.clone().show().html();
				} else if (data == false) {
					return ditolakStatus.clone().show().html();
				}
			},
		},
		{
			"targets": 8,
			"title": "Sesi",
			"data": "book_times_summary",
			"name": "book_times_summary",
			"searchable": false,
			"visible": true,
			// "width": "50%",
			"render": function (data, type, full, meta) {
				// console.log(data);
				let result = [];
				let resultHTML = '<div style="white-space: nowrap;">'
				let resultHTML2 = '</div>'
				// seperate data string to get datetime string
				rows = data.split(',');
				// Remove last elm if it's empty string
				if (!rows[rows.length-1]) rows.pop(); 
				for (const row of rows) {
					let [date, host] = row.split(" - ");
					// Parse UTC timezome to locale timezone
					date = new Date(date);
					// Print date with format
					date = `${pz(date.getDate())}-${pz(date.getMonth() + 1)}-${date.getFullYear()} ${pz(date.getHours())}:${pz(date.getMinutes())}:${pz(date.getSeconds())}`
					// recombine data string
					result.push(`${date} <br> - ${host}`)
				}
				result = result.join('<br>')
				return resultHTML + result + resultHTML2;
			},
		},
		{
			"targets": 9,
			"title": "Aksi",
			"data": null,
			"name": null,
			"searchable": false,
			"visible": true,
			"render": function (data, type, full, meta) {
				let resultHTML = '<div style="white-space: nowrap;">'
				resultHTML += viewBtn.createButton(full.id).html();
				resultHTML += editBtn.createButton(full.id).html();
				resultHTML += delBtn.createButton(full.id).html();
				resultHTML += '</div>'
				return resultHTML;
			}
		},
	],
	initComplete: function () {
		// Apply the search
		this.api().columns().every(function () {
			var column = this;

			$('input', this.footer()).on('keyup change clear', $.debounce(250, true, function (e) {
				console.log("test run");
				if (column.search() !== this.value) {
					column.search(this.value).draw();
				}
			}));

			$('select', this.footer()).on('change', function () {
				let val = $(this).val();
				column.search(val, false, false, true).draw();
			});
		});
	}
});

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