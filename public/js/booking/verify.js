function setujuBooking() {
	document.getElementById('verify').value = "setuju";
	document.getElementsByTagName('form')[0].submit();
}
function tolakBooking() {
	document.getElementById('verify').value = "tolak";
	document.getElementsByTagName('form')[0].submit();
}