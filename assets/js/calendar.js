/**
 * Tickets Calendar JavaScript
 * Month / Week / Day views, matching the standalone Calendar module's UX.
 */

// API base path - can be overridden by page before loading this script
const API_BASE = window.API_BASE || 'api/';

// Locale for Intl date/time formatting — sourced from <html lang> so it matches
// the user's selected interface language. Falls back to en-GB if the bridge
// hasn't run or the page didn't set <html lang>.
const PAGE_LOCALE = (document.documentElement.lang || 'en-GB');

// Translation lookup with a graceful fallback when the i18n.js bridge isn't loaded.
function tr(key, params) {
    return (typeof window.t === 'function') ? window.t(key, params) : key;
}

// State
let currentView = 'month';
let currentDate = new Date();
let scheduledTickets = [];

// Day order: Monday-first to match UK conventions and the legacy tickets
// calendar behaviour. Index 0 = Monday, index 6 = Sunday.
const WEEKDAY_KEYS = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
const MONTH_KEYS = ['january', 'february', 'march', 'april', 'may', 'june',
                    'july', 'august', 'september', 'october', 'november', 'december'];

function shortWeekdayLabel(weekdayIndex) {
    // Render short labels via Intl to respect locale; weekdayIndex is Monday=0..Sunday=6.
    // Build a reference date for that weekday and format it short.
    const refDayOfWeek = (weekdayIndex + 1) % 7; // Convert to Sun=0..Sat=6
    const ref = new Date(2024, 0, 7 + refDayOfWeek); // 7 Jan 2024 = Sunday
    return ref.toLocaleDateString(PAGE_LOCALE, { weekday: 'short' });
}

function monthLabel(monthIndex) {
    return tr('common.calendar.months.' + MONTH_KEYS[monthIndex]);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    renderCalendar();
});

// Switch view
function setView(view) {
    currentView = view;
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.view === view);
    });
    renderCalendar();
}

// Navigate to today
function goToToday() {
    currentDate = new Date();
    renderCalendar();
}

// Navigate to previous period (depends on current view)
function navigatePrev() {
    if (currentView === 'month') {
        currentDate.setMonth(currentDate.getMonth() - 1);
    } else if (currentView === 'week') {
        currentDate.setDate(currentDate.getDate() - 7);
    } else {
        currentDate.setDate(currentDate.getDate() - 1);
    }
    renderCalendar();
}

// Navigate to next period (depends on current view)
function navigateNext() {
    if (currentView === 'month') {
        currentDate.setMonth(currentDate.getMonth() + 1);
    } else if (currentView === 'week') {
        currentDate.setDate(currentDate.getDate() + 7);
    } else {
        currentDate.setDate(currentDate.getDate() + 1);
    }
    renderCalendar();
}

// Legacy month nav (kept for backwards compatibility with anything still calling it)
function changeMonth(delta) {
    currentDate.setMonth(currentDate.getMonth() + delta);
    renderCalendar();
}

// Render the calendar for the current view
async function renderCalendar() {
    updateTitle();
    await loadScheduledTicketsForRange();

    const grid = document.getElementById('calendarGrid');
    if (currentView === 'month') {
        renderMonthView(grid);
    } else if (currentView === 'week') {
        renderWeekView(grid);
    } else {
        renderDayView(grid);
    }
}

// Update the calendar title
function updateTitle() {
    const titleEl = document.getElementById('calendarTitle');
    if (currentView === 'month') {
        titleEl.textContent = `${monthLabel(currentDate.getMonth())} ${currentDate.getFullYear()}`;
    } else if (currentView === 'week') {
        const weekStart = getWeekStart(currentDate);
        const weekEnd = new Date(weekStart);
        weekEnd.setDate(weekEnd.getDate() + 6);
        if (weekStart.getMonth() === weekEnd.getMonth()) {
            titleEl.textContent = `${monthLabel(weekStart.getMonth())} ${weekStart.getDate()} – ${weekEnd.getDate()}, ${weekStart.getFullYear()}`;
        } else {
            titleEl.textContent = `${monthLabel(weekStart.getMonth())} ${weekStart.getDate()} – ${monthLabel(weekEnd.getMonth())} ${weekEnd.getDate()}, ${weekEnd.getFullYear()}`;
        }
    } else {
        titleEl.textContent = `${monthLabel(currentDate.getMonth())} ${currentDate.getDate()}, ${currentDate.getFullYear()}`;
    }
}

// Compute the date range needed for the current view and load tickets
async function loadScheduledTicketsForRange() {
    let start, end;

    if (currentView === 'month') {
        // Start from the Monday on or before the 1st, span 6 weeks
        const first = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
        start = getWeekStart(first);
        end = new Date(start);
        end.setDate(end.getDate() + 42);
    } else if (currentView === 'week') {
        start = getWeekStart(currentDate);
        end = new Date(start);
        end.setDate(end.getDate() + 7);
    } else {
        start = new Date(currentDate);
        start.setHours(0, 0, 0, 0);
        end = new Date(start);
        end.setDate(end.getDate() + 1);
    }

    const startStr = formatDateForCompare(start);
    const endStr = formatDateForCompare(end);

    try {
        const response = await fetch(`${API_BASE}get_scheduled_tickets.php?start=${startStr}&end=${endStr}&_t=${Date.now()}`);
        const data = await response.json();
        if (data.success) {
            scheduledTickets = data.tickets.map(t => ({
                ...t,
                date: t.work_start_datetime.split('T')[0],
                time: new Date(t.work_start_datetime).toLocaleTimeString(PAGE_LOCALE, { hour: '2-digit', minute: '2-digit' })
            }));
        } else {
            console.error('Error loading tickets:', data.error);
            scheduledTickets = [];
        }
    } catch (error) {
        console.error('Error:', error);
        scheduledTickets = [];
    }
}

// Get start of week as Monday (Monday-first week)
function getWeekStart(date) {
    const d = new Date(date);
    const dayOfWeek = d.getDay(); // 0 = Sunday, 1 = Monday, …, 6 = Saturday
    const offsetToMonday = dayOfWeek === 0 ? -6 : 1 - dayOfWeek;
    d.setDate(d.getDate() + offsetToMonday);
    d.setHours(0, 0, 0, 0);
    return d;
}

// Format date as YYYY-MM-DD using local time
function formatDateForCompare(date) {
    const yyyy = date.getFullYear();
    const mm = String(date.getMonth() + 1).padStart(2, '0');
    const dd = String(date.getDate()).padStart(2, '0');
    return `${yyyy}-${mm}-${dd}`;
}

// Render month view
function renderMonthView(container) {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    const firstDay = new Date(year, month, 1);
    const startDate = getWeekStart(firstDay);

    let html = '<div class="month-grid">';

    // Header row
    html += '<div class="month-header">';
    for (let i = 0; i < 7; i++) {
        html += `<div class="month-header-cell">${shortWeekdayLabel(i)}</div>`;
    }
    html += '</div>';

    // Days
    html += '<div class="month-body">';
    const current = new Date(startDate);
    for (let i = 0; i < 42; i++) {
        const isOtherMonth = current.getMonth() !== month;
        const isToday = current.getTime() === today.getTime();
        const dayOfWeek = current.getDay();
        const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;
        const dateStr = formatDateForCompare(current);
        const dayTickets = scheduledTickets.filter(t => t.date === dateStr);

        let classes = 'month-day';
        if (isOtherMonth) classes += ' other-month';
        if (isToday) classes += ' today';
        if (isWeekend) classes += ' weekend';

        html += `<div class="${classes}">`;
        html += `<div class="day-number">${current.getDate()}</div>`;
        html += '<div class="day-tickets">';

        const maxDisplay = 3;
        dayTickets.slice(0, maxDisplay).forEach(ticket => {
            let priorityClass = '';
            if (ticket.priority === 'High') priorityClass = ' priority-high';
            else if (ticket.priority === 'Low') priorityClass = ' priority-low';

            html += `<div class="calendar-ticket${priorityClass}" onclick="showTicketDetail(${ticket.id})" title="${escapeHtml(ticket.subject)}">
                        <span class="ticket-time">${ticket.time}</span>
                        ${escapeHtml(ticket.ticket_number)}
                     </div>`;
        });

        if (dayTickets.length > maxDisplay) {
            const moreCount = dayTickets.length - maxDisplay;
            html += `<div class="more-tickets" onclick="event.stopPropagation(); setView('day'); currentDate = new Date('${dateStr}T00:00:00'); renderCalendar();">${escapeHtml(tr('tickets.calendar.x_more', { count: moreCount }))}</div>`;
        }

        html += '</div></div>';
        current.setDate(current.getDate() + 1);
    }
    html += '</div></div>';

    container.innerHTML = html;
}

// Render week view (Mon–Sun across the top, 24-hour day on the side)
function renderWeekView(container) {
    const weekStart = getWeekStart(currentDate);
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    let html = '<div class="week-grid">';

    // Header row (sticky)
    html += '<div class="week-header"><div class="week-header-time"></div><div class="week-header-days">';
    for (let i = 0; i < 7; i++) {
        const day = new Date(weekStart);
        day.setDate(day.getDate() + i);
        const isToday = day.getTime() === today.getTime();
        const isWeekend = i >= 5;
        html += `<div class="week-header-day${isToday ? ' today' : ''}${isWeekend ? ' weekend' : ''}">
                    <div class="week-day-name">${shortWeekdayLabel(i)}</div>
                    <div class="week-day-number">${day.getDate()}</div>
                 </div>`;
    }
    html += '</div></div>';

    // Body
    html += '<div class="week-body"><div class="week-time-column">';
    for (let hour = 0; hour < 24; hour++) {
        html += `<div class="week-time-slot-label">${formatHourLabel(hour)}</div>`;
    }
    html += '</div><div class="week-days-container">';

    for (let i = 0; i < 7; i++) {
        const day = new Date(weekStart);
        day.setDate(day.getDate() + i);
        const isToday = day.getTime() === today.getTime();
        const isWeekend = i >= 5;
        const dateStr = formatDateForCompare(day);

        html += `<div class="week-day-column${isToday ? ' today' : ''}${isWeekend ? ' weekend' : ''}">`;
        for (let hour = 0; hour < 24; hour++) {
            html += `<div class="week-time-slot"></div>`;
        }

        // Tickets in this day as 1-hour blocks at their work_start_datetime
        const dayTickets = scheduledTickets.filter(t => t.date === dateStr);
        dayTickets.forEach(ticket => {
            const dt = new Date(ticket.work_start_datetime);
            const startHour = dt.getHours();
            const startMinutes = dt.getMinutes();
            const top = startHour * 60 + startMinutes;
            const height = 60; // one hour default

            let priorityClass = '';
            if (ticket.priority === 'High') priorityClass = ' priority-high';
            else if (ticket.priority === 'Low') priorityClass = ' priority-low';

            html += `<div class="week-event${priorityClass}" style="top: ${top}px; height: ${height}px;"
                          onclick="showTicketDetail(${ticket.id})" title="${escapeHtml(ticket.subject)}">
                          <div class="week-event-title">${escapeHtml(ticket.ticket_number)}</div>
                          <div class="week-event-time">${ticket.time}</div>
                     </div>`;
        });

        html += '</div>';
    }
    html += '</div></div></div>';

    container.innerHTML = html;
}

// Render day view (single column, 24-hour day)
function renderDayView(container) {
    const viewDate = new Date(currentDate);
    viewDate.setHours(0, 0, 0, 0);
    const dateStr = formatDateForCompare(viewDate);
    const dayTickets = scheduledTickets.filter(t => t.date === dateStr);

    let html = '<div class="day-grid">';

    // Header
    html += '<div class="day-header"><div class="day-header-info">';
    html += `<div class="day-header-date">${currentDate.getDate()}</div>`;
    html += `<div class="day-header-weekday">${currentDate.toLocaleDateString(PAGE_LOCALE, { weekday: 'long', month: 'long', year: 'numeric' })}</div>`;
    html += '</div></div>';

    // Body
    html += '<div class="day-body"><div class="day-time-column">';
    for (let hour = 0; hour < 24; hour++) {
        html += `<div class="week-time-slot-label">${formatHourLabel(hour)}</div>`;
    }
    html += '</div><div class="day-events-column">';

    for (let hour = 0; hour < 24; hour++) {
        html += `<div class="day-time-slot"></div>`;
    }

    dayTickets.forEach(ticket => {
        const dt = new Date(ticket.work_start_datetime);
        const startHour = dt.getHours();
        const startMinutes = dt.getMinutes();
        const top = startHour * 60 + startMinutes;
        const height = 60;

        let priorityClass = '';
        if (ticket.priority === 'High') priorityClass = ' priority-high';
        else if (ticket.priority === 'Low') priorityClass = ' priority-low';

        html += `<div class="day-event${priorityClass}" style="top: ${top}px; height: ${height}px;"
                      onclick="showTicketDetail(${ticket.id})">
                      <div class="day-event-title">${escapeHtml(ticket.ticket_number)} — ${escapeHtml(ticket.subject)}</div>
                      <div class="day-event-time">${ticket.time}</div>
                 </div>`;
    });

    html += '</div></div></div>';

    container.innerHTML = html;
}

function formatHourLabel(hour) {
    // Localised hour label — uses 12h or 24h per locale conventions.
    const ref = new Date();
    ref.setHours(hour, 0, 0, 0);
    return ref.toLocaleTimeString(PAGE_LOCALE, { hour: 'numeric' });
}

// Show ticket detail modal
function showTicketDetail(ticketId) {
    const ticket = scheduledTickets.find(t => t.id === ticketId);
    if (!ticket) return;

    document.getElementById('ticketModalTitle').textContent = ticket.ticket_number;

    const body = document.getElementById('ticketModalBody');
    body.innerHTML = `
        <div class="ticket-detail-subject">${escapeHtml(ticket.subject)}</div>
        <div class="ticket-detail">
            <div class="ticket-detail-row">
                <div class="ticket-detail-label">${escapeHtml(tr('tickets.calendar.modal.scheduled'))}</div>
                <div class="ticket-detail-value">${formatDateTime(ticket.work_start_datetime)}</div>
            </div>
            <div class="ticket-detail-row">
                <div class="ticket-detail-label">${escapeHtml(tr('tickets.calendar.modal.status'))}</div>
                <div class="ticket-detail-value">${ticket.status}</div>
            </div>
            <div class="ticket-detail-row">
                <div class="ticket-detail-label">${escapeHtml(tr('tickets.calendar.modal.priority'))}</div>
                <div class="ticket-detail-value">${ticket.priority}</div>
            </div>
            <div class="ticket-detail-row">
                <div class="ticket-detail-label">${escapeHtml(tr('tickets.calendar.modal.requester'))}</div>
                <div class="ticket-detail-value">${escapeHtml(ticket.requester_name || ticket.requester_email || tr('tickets.calendar.na'))}</div>
            </div>
            <div class="ticket-detail-row">
                <div class="ticket-detail-label">${escapeHtml(tr('tickets.calendar.modal.department'))}</div>
                <div class="ticket-detail-value">${escapeHtml(ticket.department_name || tr('tickets.calendar.unassigned'))}</div>
            </div>
            <div class="ticket-detail-row">
                <div class="ticket-detail-label">${escapeHtml(tr('tickets.calendar.modal.owner'))}</div>
                <div class="ticket-detail-value">${escapeHtml(ticket.owner_name || tr('tickets.calendar.unassigned'))}</div>
            </div>
        </div>
    `;

    const inboxUrl = window.INBOX_URL || 'inbox.php';
    document.getElementById('ticketModalLink').href = `${inboxUrl}?ticket=${ticket.id}`;

    document.getElementById('ticketModal').classList.add('active');
}

// Close ticket modal
function closeTicketModal() {
    document.getElementById('ticketModal').classList.remove('active');
}

// Utility functions
function escapeHtml(text) {
    if (text === null || text === undefined) return '';
    const div = document.createElement('div');
    div.textContent = String(text);
    return div.innerHTML;
}

function formatDateTime(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    const datePart = date.toLocaleDateString(PAGE_LOCALE, {
        weekday: 'short',
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
    const timePart = date.toLocaleTimeString(PAGE_LOCALE, { hour: '2-digit', minute: '2-digit' });
    return tr('tickets.calendar.date_at_time', { date: datePart, time: timePart });
}
