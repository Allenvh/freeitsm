# browser-extension/background.js

> JavaScript - 181 lines

FreeITSM Watchtower — Background Service Worker Polls the Watchtower API on a schedule and updates the badge

## Functions

| Function | Line | What it does |
|---|--:|---|
| `setupAlarm()` | 43 | Recreates the 'watchtower-poll' alarm at the saved poll interval (default 5 minutes). |
| `fetchDashboard()` | 54 | Fetches get_dashboard_ext.php with the saved API key, stores the result (or the error) in chrome.storage.local for the popup, and sets the toolbar badge to the attention count or '!'. |
| `computeAttentionCount(data)` | 137 | Adds up the items needing attention for the badge - urgent/high and unassigned tickets, unapproved changes, active incidents, contracts expiring in 30 days, overdue reviews, morning checks, and overdue or due-today tasks. |
| `hasUrgent(data)` | 175 | True when there are urgent/high tickets, active incidents or morning checks needing attention - the badge then turns red instead of amber. |

## API endpoints it calls

- [api/watchtower/get_dashboard_ext.php](/code/files/api/watchtower/get_dashboard_ext.php.md)

