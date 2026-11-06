# browser-extension/popup.js

> JavaScript - 387 lines

FreeITSM Watchtower — Popup Script

## Functions

| Function | Line | What it does |
|---|--:|---|
| `showNotConfigured()` | 57 | Replaces the popup content with the welcome message and a Setup button that opens the options page. |
| `showError(msg)` | 70 | Shows the error banner with a message. |
| `escapeHtml(s)` | 78 | Status names now reach the popup and are written into innerHTML. They are free text an admin types on a server this extension is pointed at, so they are escaped rather than trusted. |
| `hideError()` | 84 | Hides the error banner. |
| `updateTimestamp(iso)` | 89 | Shows when the data was last fetched - "Just now", minutes ago, or a clock time in the browser's own locale. |
| `renderDashboard(data)` | 110 | Draws every Watchtower card into the popup from the stored dashboard data. |
| `renderMorningChecks(mc)` | 128 | Returns the Checks card - done of total, with not started, needing attention, all completed or remaining. |
| `renderTickets(tk)` | 164 | Returns the Tickets card - total open, with high priority and unassigned counts, or the biggest open status. |
| `renderChanges(ch)` | 196 | Returns the Changes card - changes in the next 7 days, with unapproved and in-progress counts. |
| `renderServiceStatus(ss)` | 221 | Returns the Services card - all operational, or the count of degraded services and active incidents. |
| `renderCalendar(cal)` | 254 | Returns the Calendar card - events today and this week. |
| `renderContracts(ct)` | 274 | Returns the Contracts card - contracts expiring in 90 days, highlighting 30 days and notice periods. |
| `renderKnowledge(kb)` | 307 | Returns the Knowledge card - recent articles and any overdue reviews. |
| `renderAssets(as)` | 332 | Returns the Assets card - total assets and how many have not reported in 7 days. |
| `renderTasks(tk)` | 358 | Returns the Tasks card - open tasks, with overdue or due-today counts. |

