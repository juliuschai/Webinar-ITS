function getTimeZoneOffsetInMs() {
	return new Date().getTimezoneOffset() * -60 * 1000;
}

function onupdateDurasi() {
	let start = new Date(document.getElementById('waktuMulai').value);
	let hours = document.getElementById('durasi').value;

	let end = new Date(start.getTime() + getTimeZoneOffsetInMs() + parseFloat(hours)*3600*1000);
	document.getElementById('waktuSelesai').value = end.toISOString().substring(0, 16);
}

function onupdateWaktu() {
	let startStr = document.getElementById('waktuMulai').value;
	let endStr = document.getElementById('waktuSelesai').value;
	console.log(startStr);
	if (startStr == "" || endStr == "") {
		return;
	}
	let start = new Date(startStr);
	let end = new Date(endStr);

	document.getElementById('durasi').value = (end-start)/3600/1000;
}

onupdateWaktu();
