var unitTableElm = $('#unitTable');
// Setup - add a text input to each footer cell
var types = unitTableElm.data('types');
$.each(types, function() {
	$('#searchTypeSelect')
		.append($("<option></option>")
		.val(this.id)
		.text(this.nama));
});

// DataTable
var editButton = $('#editBtnTemplate');
var deleteButton = $('#deleteBtnTemplate');
unitTableElm.DataTable({
	processing: true,
	serverSide: true,
	ajax: unitTableElm.data('ajaxurl'),
	deferLoading: unitTableElm.data("length"),
	columnDefs:[
		{
			"targets": 0,
			"data": "id",
			"name": null,
			"searchable": false,
			"visible": false,
		},
		{
			"targets": 1,
			"title": "Nama Unit",
			"data": "nama",
			"name": "units.nama",
			"searchable": true,
			"visible": true,
		},
		{
			"targets": 2,
			"title": "Tipe Unit",
			"data": "unit_type",
			"name": "unit_types.id",
			"searchable": true,
			"visible": true,
		},
		{
			"targets": 3, 
			"title": "Edit",
			"data": null,
			"name": null,
			"searchable": false,
			"visible": true,
			"render": function (data, type, full, meta) {
				return editButton.createButton(full.id).html();
			}
		},
		{
			"targets": 4, 
			"title": "Delete",
			"data": null,
			"name": null,
			"searchable": false,
			"visible": true,
			"render": function (data, type, full, meta) {
				return deleteButton.createButton(full.id).html();
			}
		}
	],
	initComplete: function () {
		// Apply the search
		this.api().columns().every( function () {
			var column = this;
			$('input', this.footer()).on('keyup change clear', function() {
				if (column.search() !== this.value) {
					column.search(this.value).draw();
				}
			});

			$('select', this.footer()).on('change', function () {
				// let val = $.fn.dataTable.util.escapeRegex($(this).val());
				let val = $(this).val();
				// column.search( val ? '^'+val+'$' : '', true, false ).draw();
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