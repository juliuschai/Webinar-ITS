$(document).ready(function (params) {
	let eventRoute = document.getElementById('calendarData').dataset.eventroute;
	let calendar = $('#calendar').fullCalendar({
		timezone: 'local',
		header:{
			left:'prev,next today',
			center:'title',
			right:'month,agendaWeek,agendaDay', 
		},
		events: eventRoute,
		eventClick: function(info) {
			window.open(`booking/view/${info.id}`);
		}
	});
})
