import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import huLocale from '@fullcalendar/core/locales/hu';

import moment from 'moment';


window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridWeek,dayGridMonth'
        },
        plugins: [dayGridPlugin],
        initialView: 'dayGridMonth',
        locales: [ huLocale ],
        events: async (fetchInfo) => {
            const response = await axios.get('/schedule', {
                params: {
                    start: fetchInfo.startStr,
                    end: fetchInfo.endStr,
                },
            });
            const events = response.data;

            // Return the events data to the FullCalendar
            return events;
        },
        eventContent: function(info) {
            return {
                html: '<b>' + info.event.title + '   <br>' + moment(info.event.start).format('HH:mm') + '-' + moment(info.event.end).format('HH:mm'),
                classNames: [ 'my-event-class' ]
            };
        }
    });


    calendar.render();
});

