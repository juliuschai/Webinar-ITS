var beginElm = document.getElementById('waktuMulai');
var endElm = document.getElementById('waktuSelesai');
var startDateElm = document.getElementById('mulaiDateElm');
var startTimeSel = document.getElementById('mulaiTimeSelect');
var durHourSel = document.getElementById('durHour');
var durMinSel = document.getElementById('durMinute');

/**
 * returns true if waktu is valid
 */
function validateWaktu() {
	const validation = {}
	validation.check = function() {
		if (!this.diff) {
			alert('Durasi harus lebih dari 30 menit!');
			return false;
		} else if (!this.start) {
			alert('Waktu mulai tidak bisa diletakkan di masa lalu!');
			return false;
		} else {
			return true;
		}
	}
	let beginDate = new Date(beginElm.value);
	let endDate = new Date(endElm.value);
	let diffMins = (endDate.getTime() - beginDate.getTime())/60/1000;
	alert(diffMins)
	validation.diff = diffMins >= 30;
	validation.start = beginDate > new Date();
	return validation.check();
}

function textBoxesPopulate() {
	for(let i = 0; i < 24; i++) {
		let hour = pz(i);
		let opt1 = document.createElement("option");
		opt1.value = `${hour}:00`;
		opt1.innerText = `${hour}:00`;
		startTimeSel.appendChild(opt1);
		let opt2 = document.createElement("option");
		opt2.value = `${hour}:30`;
		opt2.innerText = `${hour}:30`;
		startTimeSel.appendChild(opt2);
	}
	for (let i = 0; i < 24; i++) {
		let opt = document.createElement("option");
		opt.value = i;
		opt.innerText = i;
		durHourSel.appendChild(opt);
	}
	for (let i = 0; i < 60; i++) {
		let opt = document.createElement("option");
		opt.value = i;
		opt.innerText = pz(i);
		durMinSel.appendChild(opt);
	}
}

/**
 * Pad string with leading zero
 * @param {string} str 
 */
function pz(str){
	return ("0"+str).slice(-2);
}

function populateWaktus() {
	textBoxesPopulate();

	beginStr = beginElm.value;
	endStr = endElm.value;
	var beginDate, durHour, durMin, startTimeStr;
	console.log(beginStr);
	console.log(endStr);
	if (beginStr && endStr) {
		beginDate = new Date(beginStr);
		let endDate = new Date(endStr);

		durasi = (endDate.getTime() - beginDate.getTime())/60/1000;
		durHour = Math.floor(durasi/60);
		durMin = durasi%60;
	} else {
		beginDate = new Date();
		
		durHour = 0;
		durMin = 30;
	}
	if (beginDate.getMinutes()<30) {
		beginDate.setMinutes(0);
	} else {
		beginDate.setMinutes(30);
	}
	startTimeStr = `${pz(beginDate.getHours())}:${pz(beginDate.getMinutes())}`;

	startDateElm.value = `${beginDate.getYear()+1900}-${pz(beginDate.getMonth()+1)}-${pz(beginDate.getDate())}`;
	startTimeSel.value = startTimeStr;
	durHourSel.value = durHour;
	durMinSel.value = durMin;

}

populateWaktus();
updateWaktu();

function updateWaktu() {
	let startDate = (startDateElm.value);
	let startTime = (startTimeSel.value);
	let beginStr = `${startDate} ${startTime}`;
	let beginDate = new Date(beginStr);
	beginElm.value = beginStr;
	let endDate = new Date(beginDate);
	endDate.setHours(endDate.getHours()+parseInt(durHourSel.value), endDate.getMinutes()+parseInt(durMinSel.value), 0);

	beginElm.value = new Date(beginDate-getTimeZoneOffsetInMs()).toISOString().substring(0, 16);
	endElm.value = new Date(endDate-getTimeZoneOffsetInMs()).toISOString().substring(0, 16);
}

function getTimeZoneOffsetInMs() {
	return new Date().getTimezoneOffset() * 60 * 1000;
}

function submitForm() {
	if (validateWaktu()) {
		document.getElementById('bookingForm').submit();
	}
}
