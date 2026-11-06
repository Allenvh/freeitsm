# includes/service_status_planned.php

> PHP - 346 lines

Service Status - planned maintenance (3.2.0).

"Move day: phones down Saturday 08:00-14:00", announced on Tuesday.

─────────────────────────────────────────────────────────────────────────────
🔑 NOT AN INCIDENT UNTIL IT STARTS

About fifteen readers decide whether an incident is open from its STATUS
(service_incident_statuses.is_resolved) and take its start from
created_datetime: the board, the portal, Watchtower, uptime, Report Packs, the
REST API, Warbot. None of them knows a start time. An incident created on
Tuesday for Saturday would show the phones "Under Maintenance" for four days
and count from Tuesday.

So the plan waits in status_planned, shown as Upcoming, and nothing else in
the product sees it. At planned_start it becomes an ORDINARY incident, made
through ServiceStatusService::saveIncident() - so its workflows, update log
and every reader behave exactly as for one typed in by hand - and its
timestamps are BACKDATED to planned_start, so a cron that runs at 08:47 still
gives an uptime record that starts at 08:00. At planned_end it is resolved the
same way. Not one existing reader had to change.

  scheduled ─(planned_start)→ started ─(planned_end)→ finished
      └─ cancelled (by hand, before it starts)

planned_end NULL = no automatic end: somebody resolves the incident by hand.
Resolving it by hand early is respected - the end step only records
"finished" for an incident that is already resolved.

─────────────────────────────────────────────────────────────────────────────
WHO MOVES IT ON

`statusPlannedRun()`: the scheduled workflow cron (includes/workflow_scheduled.php),
and `statusPlannedDue()` at the top of the readers people look at - the board,
the portal, Watchtower - so a start is seen on time even with no cron. Each
step is claimed with a compare-and-set on `state`, so overlapping runs cannot
start one plan twice.

The incident is made AS the person who scheduled it (created_by_id), so its
history says who planned the work rather than "System".

## Functions

| Function | Line | What it does |
|---|--:|---|
| [statusPlannedReady()](/code/functions/statusPlannedReady.md) | 51 | Has Database Verification created the tables? |
| [statusPlannedUtc()](/code/functions/statusPlannedUtc.md) | 66 | 'Y-m-d H:i:s' UTC from anything `strtotime()` reads as UTC (the page sends ISO from `inputToUTC()`). |
| [statusPlannedSave()](/code/functions/statusPlannedSave.md) | 82 | Schedule (no id) or change (id, while still scheduled) planned maintenance. `$in`: title, comment, start, end?, services [{service_id, impact_level_id}], project_id?. A start that has already passed starts it at once. Returns the id. |
| [statusPlannedCancel()](/code/functions/statusPlannedCancel.md) | 130 | Cancel planned maintenance that has not started. |
| [statusPlannedLoad()](/code/functions/statusPlannedLoad.md) | 142 | Loads one planned maintenance row; throws not found when it does not exist. |
| [statusPlannedList()](/code/functions/statusPlannedList.md) | 157 | Planned maintenance for a list: scheduled and in progress by default, soonest first, each with its services. `$opts`: project_id, states, limit. |
| [statusPlannedDue()](/code/functions/statusPlannedDue.md) | 199 | Is anything due to start or end? One indexed query - cheap enough for every page load. |
| [statusPlannedRun()](/code/functions/statusPlannedRun.md) | 219 | Start what is due and finish what has ended. `$onlyId` = one plan (just saved). |
| [statusPlannedActor()](/code/functions/statusPlannedActor.md) | 262 | The person who scheduled it, as the actor of the incident's history. |
| [statusPlannedStatusId()](/code/functions/statusPlannedStatusId.md) | 279 | The status a started maintenance incident takes: the first open status that is not the starting one ("Identified" on a stock install). "Investigating" would say nobody knows what is happening - the opposite of planned work. |
| [statusPlannedStart()](/code/functions/statusPlannedStart.md) | 294 | Starts a planned maintenance: opens an external incident on its services and backdates it to the planned start. |
| [statusPlannedFinish()](/code/functions/statusPlannedFinish.md) | 323 | Finishes a planned maintenance: resolves its incident with the first resolved status and backdates the end to the planned end. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 | `statusPlannedReady` | read [status_planned](/code/tables/status_planned.md) |  |
| 55 | `statusPlannedReady` | read [status_planned_services](/code/tables/status_planned_services.md) |  |
| 104 | `statusPlannedSave` | **update** [status_planned](/code/tables/status_planned.md) (`title`, `comment`, `planned_start_datetime`, `planned_end_datetime`, `updated_datetime`) |  |
| 106 | `statusPlannedSave` | **delete** [status_planned_services](/code/tables/status_planned_services.md) |  |
| 108 | `statusPlannedSave` | **insert** [status_planned](/code/tables/status_planned.md) (`title`, `comment`, `planned_start_datetime`, `planned_end_datetime`, `state`, `project_id`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |
| 113 | `statusPlannedSave` | **insert** [status_planned_services](/code/tables/status_planned_services.md) (`planned_id`, `service_id`, `impact_level_id`) |  |
| 134 | `statusPlannedCancel` | **update** [status_planned](/code/tables/status_planned.md) (`state`, `updated_datetime`) |  |
| 144 | `statusPlannedLoad` | read [status_planned](/code/tables/status_planned.md) |  |
| 165 | `statusPlannedList` | read [status_planned](/code/tables/status_planned.md), read [analysts](/code/tables/analysts.md), read [projects](/code/tables/projects.md) |  |
| 176 | `statusPlannedList` | read [status_planned_services](/code/tables/status_planned_services.md), read [status_services](/code/tables/status_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 203 | `statusPlannedDue` | read [status_planned](/code/tables/status_planned.md) |  |
| 225 | `statusPlannedRun` | read [status_planned](/code/tables/status_planned.md) |  |
| 228 | `statusPlannedRun` | **update** [status_planned](/code/tables/status_planned.md) (`state`, `updated_datetime`) |  |
| 236 | `statusPlannedRun` | **update** [status_planned](/code/tables/status_planned.md) (`state`) |  |
| 241 | `statusPlannedRun` | read [status_planned](/code/tables/status_planned.md) |  |
| 243 | `statusPlannedRun` | **update** [status_planned](/code/tables/status_planned.md) (`state`, `updated_datetime`) |  |
| 250 | `statusPlannedRun` | **update** [status_planned](/code/tables/status_planned.md) (`state`) |  |
| 267 | `statusPlannedActor` | read [analysts](/code/tables/analysts.md) |  |
| 281 | `statusPlannedStatusId` | read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 282 | `statusPlannedStatusId` | read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 296 | `statusPlannedStart` | read [status_planned_services](/code/tables/status_planned_services.md) |  |
| 309 | `statusPlannedStart` | **update** [status_incidents](/code/tables/status_incidents.md) (`created_datetime`) |  |
| 310 | `statusPlannedStart` | **update** [status_incident_updates](/code/tables/status_incident_updates.md) (`created_datetime`) |  |
| 312 | `statusPlannedStart` | **update** [status_planned](/code/tables/status_planned.md) (`incident_id`) |  |
| 327 | `statusPlannedFinish` | read [status_incidents](/code/tables/status_incidents.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 332 | `statusPlannedFinish` | read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 340 | `statusPlannedFinish` | **update** [status_incidents](/code/tables/status_incidents.md) (`resolved_datetime`) |  |
| 342 | `statusPlannedFinish` | **update** [status_incident_updates](/code/tables/status_incident_updates.md) (`created_datetime`) |  |

## How it hangs together

**Includes:** [includes/services/service_status.php](/code/files/includes/services/service_status.php.md), [includes/service_impact_levels.php](/code/files/includes/service_impact_levels.php.md)

**Included by:** [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md), [api/service-status/get_dashboard.php](/code/files/api/service-status/get_dashboard.php.md), [api/service-status/planned.php](/code/files/api/service-status/planned.php.md), [includes/projects/ai.php](/code/files/includes/projects/ai.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md), [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md), [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md)

