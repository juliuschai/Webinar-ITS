/**
 * Pad string with leading zero
 * @param {string} str 
 */
function pz(str){
	return ("0"+str).slice(-2);
}

var nDenied = 0;
var nAccepted = 0;

(function populateDurasis() {
	let $startsDisplay = $('.waktuMulaiDisplay');
	let $starts = $('.waktuMulai');
	let $ends = $('.waktuSelesai');
	let $durasis = $('.durasi');
	for (let i = 0; i < $starts.length; i++) {
		const startDate = new Date($starts.eq(i).val());
		$startsDisplay.eq(i).val(startDate.toLocaleString());
		const endDate = new Date($ends.eq(i).val());
		let durasi = (endDate.getTime() - startDate.getTime())/60/1000
		let durHour = Math.floor(durasi/60);
		let durMin = durasi%60;

		$durasis.eq(i).val(`${pz(durHour)}:${pz(durMin)}`);
	}
})();

(function populateActions() {
	$('.action').each(function(i, elm) {
		let status = $(this).find('.status').val();
		if (status == "accept") {
			acceptBooking(this);
		} else if (status == "deny") {
			denyBooking(this);
		} else {
			cancelBooking(this);
		}
	});
})();

function acceptBooking(thisElm) {
	let $div = $(thisElm).closest('.action');
	nAccepted++;
	$div.find('.status').val('accept');
	
	// if host account is not set, show error
	if ($div.find('.hostAccount').val() == "") {
		alert('Menyetujui booking harus menentukan host account!');
		return;
	}

	// change status of display
	$div.find('.hostAccount').attr('disabled', true).show();
	$div.find('.acceptButton').hide();
	$div.find('.denyButton').hide();
	$div.find('.cancelButton').show();
	// display accepted
	$div.css('background-color', 'LightGreen');
}

function denyBooking(thisElm) {
	let $div = $(thisElm).closest('.action');
	nDenied++;
	$div.find('.status').val('deny');

	// change status of display
	$div.find('.hostAccount').attr('disabled', true).hide().val('');
	$div.find('.acceptButton').hide();
	$div.find('.denyButton').hide();
	$div.find('.cancelButton').show();
	$('#alasan').attr('disabled', false);
	// display denied
	$div.css('background-color', 'LightGray');
}

function cancelBooking(thisElm) {
	let $div = $(thisElm).closest('.action');
	let $status = $div.find('.status');
	// update counter
	if ($status.val() == "accept") {
		nAccepted--;
	} else if ($status.val() == "deny") {
		nDenied--;
	}
	$status.val('');

	// change status of display
	$div.find('.hostAccount').attr('disabled', false).show();
	$div.find('.acceptButton').show();
	$div.find('.denyButton').show();
	$div.find('.cancelButton').hide();
	if (nDenied == 0) {// there are no more denied bookings 
		$('#alasan').attr('disabled', true);
	}
	// display default
	$div.css('background-color', '');
}

function denyAll() {
	if (!confirm("Apakah anda yakin mau menolak semua booking?")) {return;}
	$('.denyButton').each(function(i, elm) {
		cancelBooking(this);
		denyBooking(this);
	});
}

function submit() {
	let lastDisetujui = $('#lastDisetujui').val();
	// confirmation popup
	if (lastDisetujui == "true") {
		if (!confirm(`Perhatian! Booking ini sudah dibuatkan webinar di Zoom dan \
dikirimkan email ke user. Apakah anda yakin untuk booking ulang?`)) {
			return;
		}
	}

	let $divs = $('.action');
	let res = [];
	let validation = "";
	let alasanVal = $('#alasan').val();
	$divs.each(function(i, elm) {
		if (validation) {return;}
		$elm = $(this);
		let id = parseInt($elm.data('id'));
		let status = $elm.find('.status').val();
		let hostAccount;
		if (status == "accept") {
			hostAccount = $elm.find('.hostAccount').val();
			if (!hostAccount) {validation = "hostAccount"};
		} else if (status == "deny"){
			hostAccount = undefined;
			if (!alasanVal) {
				validation = "alasan";
			}
		}

		res.push({id: id, status: status, hostAccount: hostAccount});
	});

	if (validation == "hostAccount") {
		alert("Menyetujui booking harus menentukan host account!")
		return;
	} else if (validation == "alasan") {
		alert("Alasan dibutuhkan jika terdapat booking yang ditolak!");
		return;
	}

	let $fieldTemplate = $('.fields').clone();
	$('.fields').remove();
	let $form = $('#verifyForm');
	$form.find('.alasanField').val(alasanVal);
	for (let i = 0; i < res.length; i++) {
		const elm = res[i];

		let $field = $fieldTemplate.clone();
		$field.find('.id').attr('name', `verify[${i}][id]`).val(elm.id);
		$field.find('.status').attr('name', `verify[${i}][status]`).val(elm.status);
		$field.find('.hostAccount').attr('name', `verify[${i}][hostAccount]`).val(elm.hostAccount);

		$form.append($field);
	}
	$form.submit();
}