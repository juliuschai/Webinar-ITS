function forceTZIn(dateStr, tzInGMT) {
    let date = new Date(dateStr);
    // force timezone
    date.setTime(date.getTime() - date.getTimezoneOffset()*60000 - tzInGMT*3600000);
    return date;
}

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
    let bulanMulai = new Date($('#bulanMulai').val())
    let bulanAkhir = new Date($('#bulanAkhir').val())

    let firstDay = new Date(bulanMulai.getFullYear(), bulanMulai.getMonth(), 1);
	let lastDay = new Date(bulanAkhir.getFullYear(), bulanAkhir.getMonth() + 1, 0);

    firstDay = forceTZIn(firstDay, 7);
    lastDay = forceTZIn(lastDay, 7);

    $('#waktuMulai').val(firstDay.toISOString());
	$('#waktuAkhir').val(lastDay.toISOString());
}

function validateInput() {
	// If semuaWaktu checkbox is not checked
	if (!$('#semuaWaktu').prop('checked')) {
		// If user has not selected bulanMulai and bulanAkhir
		if (!$('#waktuMulai').val() || !$('#waktuAkhir').val()) {
			alert(`Waktu mulai dan waktu akhir belum dispesifikasi! Untuk download semua waktu booking silahkan pilih checkbox Semua Waktu!`);
			return false;
		}
	}

	return true;
}

function submitForm() {
	if (validateInput()) {
		$('#formSubmit').submit();
	}
}
