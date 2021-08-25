var tableElm = $('#tableElm');
// Setup - add a text input to each footer cell
var types = tableElm.data('types');
$.each(types, function () {
	$('#searchTypeSelect')
		.append($("<option></option>")
			.val(this.id)
			.text(this.nama));
});

function pz(str) {
	return ("0" + str).slice(-2);
}

function forceTZShow(dateStr, tzInGMT) {
    let date = new Date(dateStr);
    // force timezone
    date.setTime(date.getTime() + date.getTimezoneOffset()*60000 + tzInGMT*3600000);
    return date;
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

var datatableRes = tableElm.DataTable({
	processing: true,
	serverSide: true,
	ajax: tableElm.data('ajaxurl'),
	columnDefs: [
		{
			"targets": 0,
			"data": "id",
			"name": 'sub.id',
			"searchable": false,
			"visible": false,
		},
		{
			"targets": 1,
			"title": "Tanggal Booking",
			"data": "created_at",
			"name": "sub.created_at",
			"searchable": false,
			"visible": true,
			"render": function (data, type, full, meta) {
                let date = forceTZShow(new Date(data), 7);

                return `${pz(date.getDate())}-${pz(date.getMonth() + 1)}-${date.getFullYear()} ${pz(date.getHours())}:${pz(date.getMinutes())}:${pz(date.getSeconds())}`
			},
		},
		{
			"targets": 2,
			"title": "Tanggal Webinar",
			"data": "waktu_mulai",
			"name": "sub.waktu_mulai",
			"searchable": false,
			"visible": true,
			"render": function (data, type, full, meta) {
                let date = forceTZShow(new Date(data), 7);

                return `${pz(date.getDate())}-${pz(date.getMonth() + 1)}-${date.getFullYear()} ${pz(date.getHours())}:${pz(date.getMinutes())}:${pz(date.getSeconds())}`
			},
		},
		{
			"targets": 3,
			"title": "Nama Acara",
			"data": "nama_acara",
			"name": "sub.nama_acara",
			"searchable": true,
			"visible": true,
		},
		{
			"targets": 4,
			"title": "Penyelenggara Acara",
			"data": "nama",
			"name": "sub.nama",
			"searchable": true,
			"visible": true,
		},
		{
			"targets": 5,
			"title": "Admin DPTSI",
			"data": "admin_dptsi",
			"name": "sub.admin_dptsi",
			"defaultContent": ' - ',
			"searchable": true,
			"visible": true,
		},
		{
			"targets": 6,
			"title": "Status",
			"data": "disetujui",
			"name": "sub.disetujui",
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
			"targets": 7,
			"title": "Sesi",
			"data": "book_times_summary",
			"name": "sub.book_times_summary",
			"searchable": false,
			"visible": true,
			"render": function (data, type, full, meta) {
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
			"targets": 8,
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

datatableRes.columns('sub.created_at:name').order('desc').draw();

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
