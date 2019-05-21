import 'moment';
import {
    Calendar
} from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import allLocales from '@fullcalendar/core/locales-all';
export class fullCalendar {
    constructor(emplacement) {
        this.emplacement = emplacement;
        document.addEventListener('DOMContentLoaded', this.domContentLoaded.bind(this));
    }

    domContentLoaded() {
        if ($('#' + this.emplacement).length == 0) {
            return;
        }
        let calendarEl   = document.getElementById(this.emplacement);
        let dataCalendar = {
            'header': {
                'left'  : 'prev,next today',
                'center': 'title',
                'right' : 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            'locales'    : allLocales,
            'locale'     : $('html').attr('lang'),
            'plugins'    : [dayGridPlugin, timeGridPlugin, listPlugin],
            'defaultView': 'dayGridMonth'
        };
        let calendar     = new Calendar(calendarEl, dataCalendar);

        calendar.render();
    }
}
