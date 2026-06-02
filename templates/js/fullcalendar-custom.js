	$(document).ready(function() {

		$('#calendar').fullCalendar({

			header: {
				left: 'year,month, prev',
				center: 'title',

				right: 'next today',
			},
			defaultView: 'year',
			defaultDate: moment(),
			yearColumns: 3,
			selectable: true,
			selectHelper: true,
			lang: 'ru',
			select: function(start, end, jsEvent, view) {

				if (view.name=='year') {
					$('#calendar').fullCalendar('changeView', 'month');
					$('#calendar').fullCalendar('gotoDate', start);

				} else {
						
						const title = prompt('Введите событие:');
						let eventData;
						if (title) {
							eventData = {
								title: title,
								start: start,
								end: end
							};
							$('#calendar').fullCalendar('renderEvent', eventData, true);
						}

					}
				


				},


				firstDay: 1,
				editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: [


			{
				title: 'Соревнования по стрельбе',
				start: '2015-11-07'
			},

			{
				title: 'Игорь \n Алексеев',
				start: '2015-11-20',

			},

			{
				title: 'Соревнования по стрельбе',
				start: '2015-10-07'


			},

			]
		});

	
	$(".fc-year-button, .fc-prev-button,.fc-next-button , .fc-today-button").click(function () {
		cgahge_color ();
	});
	cgahge_color ();
});

	function cgahge_color () {

		$(".fc-content-skeleton table tbody tr td").each(function() {	
			if ( $(this).hasClass("fc-event-container") ) {
				const tdIndex = $(this).index() + 1;
				const $th = $(this).closest("table").find('thead td:nth-child(' + tdIndex + ')');
				$th.addClass("color_th");

			}
		});
	}
