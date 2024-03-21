import { Controller } from '@hotwired/stimulus';
import Routing from 'fos-router';
export default class extends Controller {
    connect() {

        document.addEventListener('DOMContentLoaded', async function() {
            let calendarElLg = document.getElementById('calendar-lg');
            let calendarLg = new FullCalendar.Calendar(calendarElLg, {
                initialView: 'dayGridWeek',
                locale: 'fr',
                firstDay : 1,
                height: 'auto',
                events: await getEvent(),
                themeSystem: 'bootstrap5'
            });
            let calendarElSm = document.getElementById('calendar-sm');
            let calendarSm = new FullCalendar.Calendar(calendarElSm, {
                initialView: 'dayGrid',
                locale: 'fr',
                firstDay : 1,
                height: 'auto',
                events: await getEvent(),
                themeSystem: 'bootstrap5'
            });
            calendarSm.render();
            calendarLg.render();
        });
    }
}

async function getEvent(){
    const url = Routing.generate('api_calendar_get_events', {
    });

    let events = await fetch(url);
    events = await events.json()
    return events.data

}
