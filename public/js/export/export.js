// Toggle disabled waktu from
function disableWaktu() {
	if ($('#semuaWaktu').prop('checked')) {
		$('#bulanMulai').attr('disabled', true);
		$('#bulanAkhir').attr('disabled', true);
		$('#waktuMulai').attr('disabled', true);
		$('#waktuAkhir').attr('disabled', true);
	} else {
		$('#bulanMulai').attr('disabled', false);
		$('#bulanAkhir').attr('disabled', false);
		$('#waktuMulai').attr('disabled', false);
		$('#waktuAkhir').attr('disabled', false);
	}
}

// Update waktu onchange bulanMulai bulanAkhir
function updateWaktu() {
	let bulanMulai = new Date($('#bulanMulai').val());
	let bulanAkhir = new Date($('#bulanAkhir').val());
	let firstDay = new Date(bulanMulai.getFullYear(), bulanMulai.getMonth(), 1);
	let lastDay = new Date(bulanAkhir.getFullYear(), bulanAkhir.getMonth() + 1, 0);
	$('#waktuMulai').val(firstDay.toISOString());
	$('#waktuAkhir').val(lastDay.toISOString());
}
