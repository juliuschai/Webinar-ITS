function acceptBooking() {
	document.getElementById('verify').value = "accept";
	document.getElementsByTagName('form')[0].submit();
}
function denyBooking() {
	document.getElementById('verify').value = "deny";
	document.getElementsByTagName('form')[0].submit();
}