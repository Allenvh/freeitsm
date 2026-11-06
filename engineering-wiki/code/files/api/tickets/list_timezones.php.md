# api/tickets/list_timezones.php

> API endpoint - 27 lines

API: List all IANA timezones grouped by region, for the calendar modal dropdown.

Returns: { groups: { 'Europe': ['Europe/London', 'Europe/Paris', ...], 'America': [...], ... } }

## The endpoint

| | |
|---|---|
| URL | `api/tickets/list_timezones.php` |
| Protected by | Signed-in analyst (session check) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md)

