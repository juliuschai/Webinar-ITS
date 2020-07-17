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
	let start = new Date(document.getElementById('waktuMulai').value);
	let end = new Date(document.getElementById('waktuSelesai').value);

	document.getElementById('durasi').value = (end-start)/3600/1000;
}

onupdateWaktu();
