/**
 * Pad string with leading zero
 * @param {string} str 
 */
function pz(str){
	return ("0"+str).slice(-2);
}

(function populateDurasis() {
	let $starts = $('.waktuMulai');
	let $ends = $('.waktuSelesai');
	let $durasis = $('.durasi');
	for (let i = 0; i < $starts.length; i++) {
		const startDate = new Date($starts[i].value);
		const endDate = new Date($ends[i].value);
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
			cancelBooking();
		}
	});
})();

var nDenied = 0;

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

function submit() {
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
			if (hostAccount) {validation = "hostAccount"};
		} else {
			hostAccount = undefined;
			if (!alasanVal) {
				validation = "alasan";
			}
		}

		res.push({id: id, status: status, hostAccount: hostAccount});
	});

	// Check if alasan exists

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
	for (const elm of res) {
		$form.append();
		let $field = $fieldTemplate.clone();
		$field.find('.id').val(res.id);
		$field.find('.status').val(res.status);
		$field.find('.hostAccount').val(res.hostAccount);
	}
	$form.submit();
}