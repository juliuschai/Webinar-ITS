var unitTypes = [], units = [];

function populateUnitSelects() {
	let elm = document.getElementById('unitDatas');
	unitTypes = JSON.parse(elm.dataset.types);
	units = JSON.parse(elm.dataset.units);
	curTypeId = parseInt(elm.dataset.curtypeid);
	curUnitId = parseInt(elm.dataset.curunitid);
	// Populate type select
	let typeSelect = document.getElementById('penyelengaraAcaraTypes')
	for (type of unitTypes) {
		var opt = document.createElement("option");
		opt.value = type.id;
		opt.innerText = type.nama; // whatever property it has
		if (type.id == curTypeId) {
			opt.setAttribute('selected', true);
		}

		// then append it to the select element
		typeSelect.appendChild(opt);
	}

	let unitSelect = document.getElementById('penyelengaraAcara')
	for (unit of units) {
		var opt = document.createElement("option");
		opt.value = unit.id;
		opt.innerText = unit.nama; // whatever property it has
		if (unit.id == curUnitId) {
			opt.setAttribute('selected', true);
		}
		// then append it to the select element
		unitSelect.appendChild(opt);
	}
}

function onchangeUnitType() {
	let curTypeId = document.getElementById('penyelengaraAcaraTypes').value;
	// hide all options except for unit_type_id
	options = document.getElementById('penyelengaraAcara').options;
	selIdx = options.selectedIndex;
	reselectLater = false
	Array.from(options).forEach(function (opt, idx) {
		if (curTypeId == units[idx].unit_type_id) {
			opt.style.display = '';
		} else {
			opt.style.display = 'none';
			if (selIdx == idx) {
				reselectLater = true;
			}
		}
	});
	// Reselect current option 
	if (reselectLater) {
		options[options.selectedIndex].removeAttribute('selected');
		for (i in options) {
			if (options[i].style.display != 'none') {
				options[i].setAttribute('selected', true);
				break;
			}
		}
	}
}

populateUnitSelects();
document.getElementById('penyelengaraAcaraTypes').onchange = onchangeUnitType;
onchangeUnitType();
