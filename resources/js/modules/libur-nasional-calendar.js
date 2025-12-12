import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

export function initLiburNasionalCalendar(containerSelector, eventsData, liverWireComponent) {
    const calendarEl = document.querySelector(containerSelector);
    
    if (!calendarEl) {
        console.error(`Calendar element not found: ${containerSelector}`);
        return null;
    }

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        locale: 'id',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        
        height: 'auto',
        contentHeight: 'auto',
        dayMaxEvents: 3,
        
        events: eventsData,
        
        // Handle event click - open edit modal
        eventClick: function(info) {
            console.log('Event clicked:', info.event.id);
            if (liverWireComponent && typeof liverWireComponent.dispatch === 'function') {
                liverWireComponent.dispatch('eventClick', { eventId: parseInt(info.event.id) });
            }
        },
        
        // Handle date click - open create modal with date
        dateClick: function(info) {
            console.log('Date clicked:', info.dateStr);
            if (liverWireComponent && typeof liverWireComponent.dispatch === 'function') {
                liverWireComponent.dispatch('dateClick', { selectedDate: info.dateStr });
            }
        },
        
        // Handle event drag and drop
        eventDrop: function(info) {
            console.log('Event dropped:', {
                eventId: info.event.id,
                start: info.event.startStr,
                end: info.event.endStr
            });
            
            if (liverWireComponent && typeof liverWireComponent.dispatch === 'function') {
                liverWireComponent.dispatch('eventDropped', {
                    eventId: parseInt(info.event.id),
                    startStr: info.event.startStr,
                    endStr: info.event.endStr
                });
            }
        },
        
        // Handle event resize
        eventResize: function(info) {
            console.log('Event resized:', {
                eventId: info.event.id,
                start: info.event.startStr,
                end: info.event.endStr
            });
            
            if (liverWireComponent && typeof liverWireComponent.dispatch === 'function') {
                liverWireComponent.dispatch('eventResized', {
                    eventId: parseInt(info.event.id),
                    startStr: info.event.startStr,
                    endStr: info.event.endStr
                });
            }
        },
        
        buttonText: {
            today: 'Hari Ini',
            month: 'Bulan',
            week: 'Minggu',
            day: 'Hari',
            list: 'List'
        }
    });

    calendar.render();
    return calendar;
}

export function destroyCalendar(calendar) {
    if (calendar) {
        calendar.destroy();
    }
}
