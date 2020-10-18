var hostTableElm = $('#hostTable');
// Setup - add a text input to each footer cell

hostTableElm.DataTable({
	processing: true,
	serverSide: true,
	ajax: hostTableElm.data('ajaxurl'),
	deferLoading: hostTableElm.data("length"),
	columnDefs:[
		{
			"targets": 0,
			"data": "id",
			"name": "id",
			"searchable": false,
			"visible": false,
		},
		{
			"targets": 1,
			"title": "Nama Host",
			"data": "nama",
			"name": "nama",
			"searchable": true,
			"visible": true,
		},
		{
			"targets": 2,
			"title": "Zoom ID",
			"data": "zoom_id",
			"name": "zoom_id",
			"searchable": true,
			"visible": true,
        },
        {
			"targets": 3,
			"title": "Zoom Email",
			"data": "zoom_email",
			"name": "zoom_email",
			"searchable": true,
			"visible": true,
        },
        {
			"targets": 4,
			"title": "Password",
			"data": "pass",
			"name": "pass",
			"searchable": true,
			"visible": true,
        },
        {
			"targets": 5,
			"title": "Kuota",
			"data": "max_peserta",
			"name": "max_peserta",
			"searchable": true,
			"visible": true,
		}
	],
	initComplete: function () {
		// Apply the search
		this.api().columns().every( function () {
			var column = this;
			$('input', this.footer()).on('keyup change clear', $.debounce(250, true, function(e) {
				if (column.search() !== this.value) {
					column.search(this.value).draw();
				}
			}));

			$('select', this.footer()).on('change', function () {
				// let val = $.fn.dataTable.util.escapeRegex($(this).val());
				let val = $(this).val();
				// column.search( val ? '^'+val+'$' : '', true, false ).draw();
				column.search(val, false, false, true).draw();
			});
		});
	}
});

dd($ajax);
