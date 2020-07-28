var userTableElm = $('#userTable');
// Setup - add a text input to each footer cell
var types = userTableElm.data('types');
$.each(types, function() {
	$('#searchTypeSelect')
		.append($("<option></option>")
		.val(this.id)
		.text(this.nama));
});

// DataTable
var revokeButton = $('#revokeAdmBtnTemplate');
var giveButton = $('#giveAdmBtnTemplate');
userTableElm.DataTable({
	processing: true,
	serverSide: true,
	ajax: userTableElm.data('ajaxurl'),
	deferLoading: userTableElm.data("length"),
	columnDefs:[
		{
			"targets": 0,
			"data": "id",
			"name": 'users.id',
			"searchable": false,
			"visible": false,
		},
		{
			"targets": 1,
			"title": "Nama",
			"data": "nama",
			"name": "users.nama",
			"searchable": true,
			"visible": true,
		},
		{
			"targets": 2,
			"title": "Email",
			"data": "email",
			"name": "email",
			"searchable": true,
			"visible": true,
		},
		{
			"targets": 3,
			"title": "Integra",
			"data": "integra",
			"name": "integra",
			"searchable": true,
			"visible": true,
		},
		{
			"targets": 4,
			"title": "Sivitas",
			"data": "sivitas",
			"name": "groups.nama",
			"searchable": true,
			"visible": true,
		},
		{
			"targets": 5,
			"title": "Admin",
			"data": "is_admin",
			"name": "is_admin",
			"searchable": true,
			"visible": true,
			"render": function(data, type, full, meta) {
				if (full.is_admin == true) {
					return '<div>Admin</div>';
				} else {
					return '<div>User</div>';
				}
			}
		},
		{
			"targets": 6, 
			"title": "Aksi",
			"data": null,
			"name": null,
			"searchable": false,
			"visible": true,
			"render": function (data, type, full, meta) {
				if (full.is_admin == true) {
					return revokeButton.createButton(full.id).html();
				} else {
					return giveButton.createButton(full.id).html();
				}
			}
		},
	],
	initComplete: function () {
		// Apply the search
		this.api().columns().every(function () {
			var column = this;

			$('input', this.footer()).on('keyup change clear', $.debounce(250, true, function(e) {
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