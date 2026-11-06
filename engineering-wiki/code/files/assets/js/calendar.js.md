# assets/js/calendar.js

> JavaScript - 1088 lines

Tickets Calendar JavaScript Month / Week / Day views, matching the standalone Calendar module's UX.

## Functions

| Function | Line | What it does |
|---|--:|---|
| `tr(key, params)` | 17 | Translation lookup with a graceful fallback when the i18n.js bridge isn't loaded. |
| `assignOwnerColours(tickets)` | 66 | Assign colours to the owners actually on screen. |
| `ownerColour(ownerId)` | 77 | The colour for a ticket owner on the calendar - the unassigned grey for nobody, the colour assigned to them on screen, or a fixed fallback from the palette. |
| `eventColourStyle(ticket)` | 107 | How an event is coloured, which depends on which question the screen is answering. |
| `calendarTicketTitle(ticket)` | 121 | The hover tooltip for an event. |
| `renderOwnerLegend()` | 135 | Repaint the legend from the tickets on screen. |
| `setScope(scope)` | 158 | Switch between my scheduled work and everyone's, and remember the choice. |
| `shortWeekdayLabel(weekdayIndex)` | 183 | The short name of a weekday (Monday = 0) in the analyst's locale. |
| `monthLabel(monthIndex)` | 192 | The translated name of a month (January = 0). |
| `setView(view)` | 203 | Switch view |
| `goToToday()` | 215 | Navigate to today |
| `navigatePrev()` | 221 | Navigate to previous period (depends on current view) |
| `navigateNext()` | 233 | Navigate to next period (depends on current view) |
| `changeMonth(delta)` | 245 | Legacy month nav (kept for backwards compatibility with anything still calling it) |
| `renderCalendar()` | 251 | Render the calendar for the current view |
| `updateTitle()` | 270 | Update the calendar title |
| `loadScheduledTicketsForRange()` | 289 | Compute the date range needed for the current view and load tickets |
| `scheduledBlockHeight(ticket, topMinutes)` | 347 | How tall a scheduled ticket draws, in pixels — the grid is one minute per pixel. |
| `scheduledBlockTop(ticket, startHour, startMinutes, dayHasAllDay)` | 368 | Where a ticket starts, in minutes-as-pixels down the day column. |
| `getWeekStart(date)` | 375 | Midnight on the Monday of the week a date falls in. |
| `formatDateForCompare(date)` | 385 | Format date as YYYY-MM-DD using local time |
| `renderMonthView(container)` | 393 | Render month view |
| `renderWeekView(container)` | 457 | Render week view (Mon–Sun across the top, 24-hour day on the side) |
| `renderDayView(container)` | 526 | Render day view (single column, 24-hour day) |
| `formatHourLabel(hour)` | 579 | The hour label down the side of the day and week views - "14" or "2 PM", following the analyst's chosen time format. |
| `draggedDurationMinutes(ticket)` | 610 | The dragged ticket's length in minutes, so a move never resizes it. |
| `onEventDragStart(e, ticketId)` | 616 | Starts dragging a scheduled ticket on the calendar, remembering which one it is. |
| `onEventDragEnd(e)` | 625 | Clears the dragging style and any highlighted drop slot when a drag ends. |
| `allowDrop(e, el)` | 631 | Lets a dragged ticket be dropped on this slot and highlights it - only one slot at a time. |
| `onDayDrop(e, dateStr)` | 649 | Drop onto a whole day (month view). Keeps the clock time. |
| `onSlotDrop(e, dateStr, columnEl)` | 671 | Drop into a day column at a pixel offset (week / day view). |
| `p(n)` | 686 | Pads a number to two digits. |
| `commitDrag(ticket, dateStr, time)` | 691 | Saves a ticket dropped at a date and time, keeping its duration. |
| `applyDrag(ticket, start, end, allDay)` | 704 | Move it on screen FIRST, then save. |
| `renderCurrentView()` | 755 | Repaint the grid from local state, without re-fetching. |
| `showTicketDetail(ticketId)` | 763 | Show ticket detail modal |
| `syncCalScheduleAllDay()` | 821 | All day means no start time and no duration, so both fields go away. |
| `setCalScheduleDuration(minutes)` | 834 | Put `minutes` in the duration list. |
| `postSchedule(body, okKey)` | 850 | POST a schedule change for the open ticket and repaint. |
| `saveScheduleFromCalendar()` | 880 | Saves the date, time, duration and all-day choice from the calendar's schedule dialog for the selected ticket. |
| `unscheduleFromCalendar()` | 904 | Asks for confirmation, then clears the selected ticket's scheduled time so it leaves the calendar. |
| `closeTicketModal()` | 922 | Close ticket modal |
| `calendarSignature(list)` | 988 | A fingerprint of what is currently drawn. |
| `calendarRefreshBlocked()` | 997 | Why a refresh is being skipped, or null if it may go ahead. |
| `calendarAutoRefresh()` | 1011 | The background refresh: reloads the scheduled tickets for the range shown and redraws only when something has changed. Skipped while the tab is hidden, a ticket is being dragged, a dialog is open or another refresh is running; a failure is logged, not shown. |
| `startCalendarAutoRefresh()` | 1040 | Starts the background refresh timer, and refreshes straight away whenever the tab becomes visible again. |
| `escapeHtml(text)` | 1052 | Utility functions |
| `formatScheduleRange(ticket)` | 1064 | "Tue 25 Aug 2026, 14:00 – 15:00", or the date alone when it is an all-day ticket — a time range on something explicitly marked all-day reads as a contradiction, and 00:00–23:59 is storage detail, not information. |
| `formatDateTime(dateStr)` | 1081 | A date and time as "weekday date at time", in the analyst's formats. |

## API endpoints it calls

- [api/system/set_user_preference.php](/code/files/api/system/set_user_preference.php.md)

## Loaded by

- [tickets/calendar.php](/code/files/tickets/calendar.php.md)

