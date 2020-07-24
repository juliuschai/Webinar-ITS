var beginElm = document.getElementById('waktuMulai');
var endElm = document.getElementById('waktuSelesai');
var durasiElm = document.getElementById('durasi');

let beginDate = new Date(beginElm.value);
let endDate = new Date(endElm.value);
let durasi = (endDate.getTime() - beginDate.getTime())/60/1000
durHour = Math.floor(durasi/60);
durMin = durasi%60;
durasiElm.value = `${pz(durHour)}:${pz(durMin)}`;

/**
 * Pad string with leading zero
 * @param {string} str 
 */
function pz(str){
	return ("0"+str).slice(-2);
}
