var orgTypes = [], orgs = [];

function populateOrgSelects() {
	let elm = document.getElementById('orgDatas');
	orgTypes = JSON.parse(elm.dataset.types);
	orgs = JSON.parse(elm.dataset.orgs);
	curTypeId = elm.dataset.curtypeid;
	curOrgId = elm.dataset.curorgid;
	// Populate type select
	let typeSelect = document.getElementById('penyelengaraAcaraTypes')
	for (type of orgTypes) {
		var opt = document.createElement("option");
		opt.value = type.id;
		opt.innerText = type.nama; // whatever property it has
		if (type.id == curTypeId) {
			opt.setAttribute('selected', true);
		}

		// then append it to the select element
		typeSelect.appendChild(opt);
	}

	let orgSelect = document.getElementById('penyelengaraAcara')
	for (org of orgs) {
		var opt = document.createElement("option");
		opt.value = org.id;
		opt.innerText = org.nama; // whatever property it has
		if (org.id == curOrgId) {
			opt.setAttribute('selected', true);
		}
		// then append it to the select element
		orgSelect.appendChild(opt);
	}
}

function onchangeOrgType() {
	let curTypeId = document.getElementById('penyelengaraAcaraTypes').value;
	// hide all options except for org_type_id
	options = document.getElementById('penyelengaraAcara').options;
	selIdx = options.selectedIndex;
	reselectLater = false
	Array.from(options).forEach(function (opt, idx) {
		if (curTypeId == orgs[idx].org_type_id) {
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

populateOrgSelects();
document.getElementById('penyelengaraAcaraTypes').onchange = onchangeOrgType;
onchangeOrgType();
