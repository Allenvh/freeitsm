# assets/js/change-calendar.js

> JavaScript - 547 lines

Change Management Calendar JavaScript Read-only calendar view of scheduled changes, filtered by status Adapted from itsm_calendar.js

## Functions

| Function | Line | What it does |
|---|--:|---|
| `loadStatuses()` | 34 | Load status filters from the change_statuses table (active rows only). Sorts by display_order so the order matches Settings. |
| `renderStatusFilters()` | 60 | Render status filter checkboxes |
| `toggleStatus(statusName)` | 73 | Toggle status filter |
| `setView(view)` | 83 | Set the current view |
| `goToToday()` | 92 | Navigate to today |
| `navigatePrev()` | 98 | Navigate to previous period |
| `navigateNext()` | 110 | Navigate to next period |
| `renderCalendar()` | 122 | Render the calendar based on current view |
| `updateTitle()` | 138 | Update the calendar title |
| `loadChanges()` | 157 | Load changes from API for current date range |
| `getDateRange()` | 176 | Get date range for current view |
| `getWeekStart(date)` | 202 | Get start of week (Sunday) |
| `formatDateForAPI(date)` | 210 | Format date for API using local time |
| `renderMonthView(container)` | 215 | Render month view |
| `renderWeekView(container)` | 273 | Render week view |
| `renderDayView(container)` | 336 | Render day view |
| `getEventsForDate(dateStr)` | 387 | Get events for a specific date |
| `getEventHour(datetime)` | 396 | Get hour from datetime string |
| `formatDateForCompare(date)` | 403 | Format date for comparison (YYYY-MM-DD) using local time |
| `formatEventTime(evt)` | 417 | Format event time for display. Change work / outage windows are NAIVE wall-clock scheduling values (typed into datetime-local inputs, stored without a zone). They are shown EXACTLY as typed for every analyst — never zone-converted — so they stay consistent with the grid placement (getEventHour / getEventsForDate work off the same raw string). parseNaiveDate (tz.js) builds a Date from the literal components, so `getHours()`/getMinutes() read back the typed values. |
| `formatTime(d)` | 422 | A time as "2 PM" or "2:30 PM". |
| `formatDatetimeRange(startStr, endStr)` | 439 | Format a datetime range for display in popup (work / outage windows). Naive wall-clock, shown as typed — see formatEventTime. |
| `formatDt(d)` | 445 | A date and time as "Mar 5, 2 PM" or "Mar 5, 2:30 PM". |
| `showChangePopup(changeId, clickEvent)` | 463 | Show change detail popup |
| `closeChangePopup()` | 512 | Close change popup |
| `openChange(id)` | 525 | Navigate to change in main view |
| `formatLocalDatetime(d)` | 530 | Format a Date object as local datetime string (YYYY-MM-DD HH:MM:SS) |
| `escapeHtml(text)` | 541 | Escape HTML for XSS prevention |

## Loaded by

- [change-management/calendar.php](/code/files/change-management/calendar.php.md)

