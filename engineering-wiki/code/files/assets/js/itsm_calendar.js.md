# assets/js/itsm_calendar.js

> JavaScript - 854 lines

ITSM Calendar Module JavaScript Handles calendar views, event CRUD, and category filtering

## Functions

| Function | Line | What it does |
|---|--:|---|
| `cal(key, english, params)` | 16 | Translate, or the English written at the call site (i18n.js). |
| `calCommon(key, english)` | 20 | Translates a key from the shared common.calendar language section, or returns the English given at the call site. |
| `dayNames()` | 38 | Short weekday names, Sunday first, in the user's language. |
| `monthNames()` | 40 | Full month names, January first, in the user's language. |
| `loadCategories()` | 49 | Load categories from API |
| `renderCategoryFilters()` | 65 | Render category filter checkboxes |
| `toggleCategory(categoryId)` | 83 | Toggle category filter |
| `setView(view)` | 93 | Set the current view |
| `goToToday()` | 102 | Navigate to today |
| `navigatePrev()` | 108 | Navigate to previous period |
| `navigateNext()` | 120 | Navigate to next period |
| `renderCalendar()` | 132 | Render the calendar based on current view |
| `updateTitle()` | 148 | Update the calendar title |
| `loadEvents()` | 167 | Load events from API for current date range |
| `getDateRange()` | 186 | Get date range for current view |
| `getWeekStart(date)` | 212 | Get start of week (Sunday) |
| `formatDateForAPI(date)` | 220 | Format date for API using local time |
| `renderMonthView(container)` | 225 | Render month view |
| `renderWeekView(container)` | 286 | Render week view |
| `renderDayView(container)` | 351 | Render day view |
| `getEventsForDate(dateStr)` | 415 | Get events for a specific date |
| `getEventHour(datetime)` | 424 | Get hour from datetime string |
| `formatDateForCompare(date)` | 431 | Format date for comparison (YYYY-MM-DD) using local time |
| `formatEventTime(event)` | 439 | Format event time for display |
| `formatTime(d)` | 444 | Formats a time as 12-hour clock with AM/PM, leaving out the minutes on the hour. |
| `openEventModal(eventId = null, dateStr = null, hour = null)` | 461 | Open event modal |
| `loadEventForEdit(eventId)` | 532 | Load event data for editing |
| `toggleAllDay()` | 566 | Toggle all-day checkbox |
| `closeEventModal()` | 573 | Close event modal |
| `saveEvent()` | 579 | Save event |
| `deleteEvent()` | 641 | Delete event |
| `showEventPopup(eventId, clickEvent)` | 673 | Show event popup (quick view) |
| `closeEventPopup()` | 704 | Close event popup |
| `editEventFromPopup()` | 709 | Edit event from popup |
| `handleDragStart(eventId, e)` | 723 | Drag and drop handlers for month view |
| `handleDragEnd(e)` | 731 | Clears the drag highlight from the month view and forgets the dragged event when a drag finishes. |
| `handleDragOver(e)` | 740 | Allows a drop on a month-view day and highlights that day as the only drop target. |
| `handleDragLeave(e)` | 753 | Removes the drop highlight when the pointer leaves a month-view day for somewhere outside it. |
| `handleDrop(newDateStr, e)` | 765 | Moves the dropped event to the new day in the month view, keeping its times and length, saves it through save_event.php and redraws the calendar. Dropping on the same day does nothing. |
| `formatLocalDatetime(d)` | 837 | Format a Date object as local datetime string (YYYY-MM-DD HH:MM:SS) |
| `escapeHtml(text)` | 848 | Escape HTML for XSS prevention |

## Loaded by

- [calendar/index.php](/code/files/calendar/index.php.md)

