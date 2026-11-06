# api/self-service/get_incident_updates.php

> API endpoint - 49 lines

API: the external updates on one incident, for the self-service portal (#99). GET ?incident_id=

Loaded on demand when somebody expands an incident, because the requester
asked for the history to be collapsed by default and most people reading a
status page never open one.

🔴 The `is_internal = 0` filter is NOT here. It lives in
`ssPortalIncidentUpdates()`, which is the only thing this endpoint calls, so
there is exactly one place that decides what an end user may read — and it is
the same place the dashboard uses. An endpoint that did its own filtering
would be a second copy of that rule, and the second copy is the one that gets
it wrong.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_incident_updates.php` |
| Protected by | Signed-in portal user (session check) |
| Reads | `GET incident_id` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/service_status_portal.php](/code/files/includes/service_status_portal.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [ssPortalIncidentUpdates()](/code/functions/ssPortalIncidentUpdates.md)

