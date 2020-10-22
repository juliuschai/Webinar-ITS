/**
 * Populates the selects that exists in the page
 */
(function populateSelects() {
	$('.mulaiTime').each(function(i, elm) {
		for(let i = 0; i < 24; i++) {
			let hour = pz(i);
			let opt1 = document.createElement("option");
			opt1.value = `${hour}:00`;
			opt1.innerText = `${hour}:00`;
			this.appendChild(opt1);
			let opt2 = document.createElement("option");
			opt2.value = `${hour}:30`;
			opt2.innerText = `${hour}:30`;
			this.appendChild(opt2);
		}
	});

	$('.durHour').each(function(i, elm) {
		for (let i = 0; i < 24; i++) {
			let opt = document.createElement("option");
			opt.value = i;
			opt.innerText = i;
			this.appendChild(opt);
		}
	});

	$('.durMinute').each(function(i, elm) {
		for (let i = 0; i < 60; i+=15) {
			let opt = document.createElement("option");
			opt.value = i;
			opt.innerText = pz(i);
			this.appendChild(opt);
		}
	});

})();

function pz(str){
	return ("0"+str).slice(-2);
}

function validateOtherFields() {
	if ($('#namaAcara').val().length <= 0) {
		alert('Nama acara harus diisi!');
		return false;
	}
	// If dokumen pendukung field is empty and filePendukung doesn't exist in db (filePendukungExists)
	if ($('#dokumenPendukung').val().length <= 0 && $('.filePendukungExists').length <= 0) {
		alert('Dokumen Pendukung dibutuhkan!');
		return false;
	}

	return true;
}

/**
 * returns true if waktu is valid
 */
function validateWaktu() {
	let beginDates = [], endDates = [];

	let $begins = $('.bookingTimesForm').find('.waktuMulai');
	$begins.each(function (i, elm) {
		beginDates.push(new Date($(this).val()));
	});
	let $ends = $('.bookingTimesForm').find('.waktuSelesai');
	$ends.each(function (i, elm) {
		endDates.push(new Date($(this).val()));
	});

	for (let i = 0; i < beginDates.length; i++) {
		const beginDate = beginDates[i];
		const endDate = endDates[i];

		if (beginDate <= new Date()) {
			alert('Waktu mulai tidak bisa diletakkan di masa lalu!');
			return false;
		}

		let diff = (endDate.getTime() - beginDate.getTime())/60/1000;
		if (diff < 30) {
			alert('Durasi harus lebih dari 30 menit!');
			return false;
		}
	}

	return true;
}

function updateWaktu(thisElm) {
	let $this = $(thisElm);
	let $durHour = $this.closest('.bookingTimesForm').find('.durHour');
	let $durMin = $this.closest('.bookingTimesForm').find('.durMinute');
	let $startDate = $this.closest('.bookingTimesForm').find('.mulaiDate');
	let $startTime = $this.closest('.bookingTimesForm').find('.mulaiTime');
	let $begin = $this.closest('.bookingTimesForm').find('.waktuMulai');
	let $end = $this.closest('.bookingTimesForm').find('.waktuSelesai');

	let beginDate = new Date(`${$startDate.val()} ${$startTime.val()}`);
	endDate = new Date(beginDate);

	endDate.setHours(
		endDate.getHours()+parseInt($durHour.val()),
		endDate.getMinutes()+parseInt($durMin.val()), 0
	);

	$begin.val(beginDate.toISOString());
	$end.val(endDate.toISOString());
}

function deleteField(thisElm) {
	let $form = $(thisElm).closest('.bookingTimesForm');
	if ($form.find('.gladi').val() == "true") {gladiFormAmnt--;}
	formAmnt--;
	$form.remove();
	formsSetIdx();
}

function submitForm() {
	if (validateOtherFields() && validateWaktu()) {
		document.getElementById('bookingForm').submit();
	}
}

var bookingTimes = $('.bookingTimesForms').data('datas');
var gladiFormAmnt = 0;
var formAmnt = 1;
var tipeZoom = '';

if (window.location.href.includes('webinar')) {
    tipeZoom = 'Webinar';
} else if (window.location.href.includes('meeting')) {
    tipeZoom = 'Meeting';
}

/**
 * Update fields from waktuMulai anda waktuSelesai
 */
if (Array.isArray(bookingTimes) && bookingTimes.length>0) {
	// Call how many times necessary to fill the bookingTimesForms
	let skippedFirst = false;
	for (let i = 0; i < bookingTimes.length; i++) {
		const bookingTime = bookingTimes[i];
		if (bookingTime.gladi === "true" || bookingTime.gladi === true){
			addGladiTimesForm();
		} else {
			if (!skippedFirst) {
				skippedFirst = true;
			} else {
				addTimesForm();
			}
		}
	}

	$forms = $('.bookingTimesForm');
	for (let i = 0; i < bookingTimes.length; i++) {
		const bookingTime = bookingTimes[i];
		$form = $forms.eq(i);

		// Populate fields
		$form.find('.id').val(bookingTime.id);
		$form.find('.gladi').val(bookingTime.gladi);
		$form.find('.waktuMulai').val(bookingTime.waktuMulai??bookingTime.waktu_mulai);
		$form.find('.waktuSelesai').val(bookingTime.waktuSelesai??bookingTime.waktu_akhir);
		if (bookingTime.maxPeserta == "1000" || bookingTime.max_peserta == "1000") {
			$form.find("input.maxPeserta[value=1000]").attr('checked', true);
		} else {
			$form.find("input.maxPeserta[value=500]").attr('checked', true);
		}

		if (bookingTime.relayITSTV == "true" || bookingTime.relay_ITSTV == true) {
			$form.find("input.relayITSTV[value=true]").attr('checked', true);
		} else {
			$form.find("input.relayITSTV[value=false]").attr('checked', true);
		}

		// Disable agreed fields
		if (bookingTime.disetujui) {
			disableBookingTime($form);
		}
	}
	// Set the value of the forms from javascript
}

function disableBookingTime($form) {
	$form.css('pointer-events','none');
	$form.css('background-color','LightGray');
	$form.find('button').attr('disabled', true).hide();
}

function addGladiTimesForm() {
	let $forms = $('.bookingGladiTimesForms')
	let length = $forms.find('.bookingTimesForm').length;

	$form = $('.bookingTimesForm').eq(0).clone();
	$form = formSetIdx($form, length);

	$form.find('.sesiTitle').text(`Sesi Gladi Resik ${gladiFormAmnt+1}`);
	$form.find('.id').val("");
	$form.find('.gladi').val(true);
	$form.find('input.maxPeserta[value=1000]').attr('checked', false).hide();
	$form.find('input.maxPeserta[value=1000] + div').hide();
	$form.find('input.maxPeserta[value=500]').attr('checked', true).show();
	$form.find("input.relayITSTV[value=true]").attr('checked', false).hide();
	$form.find("input.relayITSTV[value=true] + div").hide();
	$form.find("input.relayITSTV[value=false]").attr('checked', true).show();
	formsAddIdxFromGladi();
	$forms.append($form);
	gladiFormAmnt++;
	formAmnt++;
}

function addTimesForm() {
	let $forms = $('.bookingTimesForms')

	$form = $('.bookingTimesForm').eq(0).clone();
	$form = formSetIdx($form, formAmnt);

	$form.find('.sesiTitle').text(`Sesi ${tipeZoom} ${formAmnt-gladiFormAmnt+1}`);
	$form.find('.id').val("");
	$form.find('.gladi').val(false);
	$form.find('input.maxPeserta[value=1000]').attr('checked', false).show();
	$form.find('input.maxPeserta[value=1000] + div').show();
	$form.find('input.maxPeserta[value=500]').attr('checked', true).show();
	$form.find("input.relayITSTV[value=true]").attr('checked', false).show();
	$form.find("input.relayITSTV[value=true] + div").show();
	$form.find("input.relayITSTV[value=false]").attr('checked', true).show();
	$forms.append($form);
	formAmnt++;
}

function formsSetIdx() {
	let $forms = $('.bookingTimesForm');
	for (let i = 0; i < $forms.length; i++) {
		const $form = $forms.eq(i);
		if ($form.find('.gladi').val() == "true") {
			$form.find('.sesiTitle').text(`Sesi Gladi Resik ${i+1}`);
		} else {
			$form.find('.sesiTitle').text(`Sesi ${tipeZoom} ${i-gladiFormAmnt+1}`);
		}
		formSetIdx($form, i);
	}
}
/**
 * Adds an index to the current form
 * @param {number} i starting form idx to add
 */
function formsAddIdxFromGladi() {
	let i = gladiFormAmnt;
	let $forms = $('.bookingTimesForm');
	for (i = $forms.length-1; i >= gladiFormAmnt; i--) {
		formSetIdx($forms[i], i+1);
	}
}

/**
 * sets the the name attributes of the form elements according to the index
 * @param {JQueryHTMLElement} form the form elemnt to modify
 * @param {number} i to set the names of form
 */
function formSetIdx(form, i) {
	let $form = $(form);
	$form.find('.id').attr('name', `bookingTimes[${i}][id]`);
	$form.find('.gladi').attr('name', `bookingTimes[${i}][gladi]`);
	$form.find('.waktuMulai').attr('name', `bookingTimes[${i}][waktuMulai]`);
	$form.find('.waktuSelesai').attr('name', `bookingTimes[${i}][waktuSelesai]`);
	$form.find('.maxPeserta').attr('name', `bookingTimes[${i}][maxPeserta]`);
	$form.find('.relayITSTV').attr('name', `bookingTimes[${i}][relayITSTV]`);
	return form;
}


function populateWaktus() {
	$forms = $('.bookingTimesForm').each(function(i, elm) {
		$form = $(this);
		beginStr = $form.find('.waktuMulai').val();
		endStr = $form.find('.waktuSelesai').val();

		var beginDate, durHour, durMin, startTimeStr;
		if (beginStr && endStr) {
			beginDate = new Date(beginStr);
			let endDate = new Date(endStr);
			beginDate.setTime(beginDate.getTime());
			endDate.setTime(endDate.getTime());

			durasi = (endDate.getTime() - beginDate.getTime())/60/1000;
			durHour = Math.floor(durasi/60);
			durMin = durasi%60;
		} else {
			beginDate = new Date();

			durHour = 0;
			durMin = 0;
		}
		if (beginDate.getMinutes()<30) {
			beginDate.setMinutes(0);
		} else {
			beginDate.setMinutes(30);
		}
		startTimeStr = `${pz(beginDate.getHours())}:${pz(beginDate.getMinutes())}`;

		$form.find('.mulaiDate').val(`${beginDate.getYear()+1900}-${pz(beginDate.getMonth()+1)}-${pz(beginDate.getDate())}`);
		$form.find('.mulaiTime').val(startTimeStr);
		$form.find('.durHour').val(durHour);
		$form.find('.durMinute').val(durMin);
	});
}

populateWaktus();
