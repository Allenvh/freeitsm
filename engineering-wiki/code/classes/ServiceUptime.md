# ServiceUptime

> Class in [includes/services/service_uptime.php](/code/files/includes/services/service_uptime.php.md), line 43

## Methods

| Method | What it does |
|---|---|
| [defaultWindowDays()](#defaultwindowdays) | The install's chosen default window, clamped to one we offer. |
| [shownInPortal()](#showninportal) | Should portal users see uptime figures? Defaults to NO — see the settings tab. |
| [incidentsFor()](#incidentsfor) | Every incident that touched this service and overlaps the window. |
| [updateLogAvailable()](#updatelogavailable) | Do the phase-2 log tables exist? Cached — every service on the board asks. |
| [segmentsFor()](#segmentsfor) | One row per period this service spent at a given impact DURING an incident (discussion #59, phase 2), or null when the incident predates the log. |
| [summaryFor()](#summaryfor) | Downtime seconds in the window, and the uptime percentage. |
| [dailyStrip()](#dailystrip) | One entry per day in the window, newest LAST, for the bar strip. |
| [incidentsBetween()](#incidentsbetween) | Every incident (or update-log segment) touching this service that overlaps [`$fromUtc`, `$toUtc`). Same shape as `incidentsFor()`. An incident still open runs to now, or to the end of the range if that is earlier. |
| [summaryBetween()](#summarybetween) | Uptime over [`$fromUtc`, `$toUtc`). Downtime is clipped to the range AND to now, so a range running into the future is measured only up to the present. |
| [dailyStripBetween()](#dailystripbetween) | One entry per LOCAL day from `$fromDate` to `$toDate` inclusive (Y-m-d in `$tz`), oldest first - the bar strip for a report. Same states as `dailyStrip()`. |
| [unionSeconds()](#unionseconds) | Merge overlapping [start, end] pairs and total the result. |
| [spanSeconds()](#spanseconds) | Seconds between two UTC stamps; an open incident runs to now. |
| [humanDuration()](#humanduration) | "2h 15m" / "45m" / "3d 4h" — the shape the request asked for. |

### defaultWindowDays

*public static* - line 54

The install's chosen default window, clamped to one we offer.

```php
function defaultWindowDays(PDO $conn): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 57 | `ServiceUptime::defaultWindowDays` | read [system_settings](/code/tables/system_settings.md) |  |

**Called from:** [api/service-status/get_service_history.php](/code/files/api/service-status/get_service_history.php.md), [service-status/settings/index.php](/code/files/service-status/settings/index.php.md)

### shownInPortal

*public static* - line 70

Should portal users see uptime figures? Defaults to NO — see the settings tab.

```php
function shownInPortal(PDO $conn): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 73 | `ServiceUptime::shownInPortal` | read [system_settings](/code/tables/system_settings.md) |  |

**Called from:** [service-status/settings/index.php](/code/files/service-status/settings/index.php.md)

### incidentsFor

*public static* - line 92

Every incident that touched this service and overlaps the window.

Returned newest first, each with its own start, end (null = ongoing) and
the impact level recorded against THIS service.

```php
function incidentsFor(PDO $conn, int $serviceId, int $windowDays): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$serviceId` | `int` |  | the service (status_services.id) |
| `$windowDays` | `int` |  | how many days back to look; an incident counts if it is still open or ended inside the window |

**Returns:** array<int,array{incident_id:int,title:string,impact:string,colour:?string, counts:bool,started:string,ended:?string,seconds:int,ongoing:bool}>

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 107 | `ServiceUptime::incidentsFor` | read [status_incident_update_services](/code/tables/status_incident_update_services.md), read [status_incident_updates](/code/tables/status_incident_updates.md) |  |
| 112 | `ServiceUptime::incidentsFor` | read [status_incidents](/code/tables/status_incidents.md), read [status_incident_services](/code/tables/status_incident_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |

**Called from:** [api/service-status/get_service_history.php](/code/files/api/service-status/get_service_history.php.md)

### updateLogAvailable

*private static* - line 177

Do the phase-2 log tables exist? Cached — every service on the board asks.

An install that has not run Database Verification since phase 2 shipped
still works: the log clause is left out of the query and every incident
falls back to its own start and end, which is exactly phase-1 behaviour.

```php
function updateLogAvailable(PDO $conn): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 184 | `ServiceUptime::updateLogAvailable` | read [status_incident_update_services](/code/tables/status_incident_update_services.md) |  |

### segmentsFor

*private static* - line 212

One row per period this service spent at a given impact DURING an incident (discussion #59, phase 2), or null when the incident predates the log.

Each update is a full snapshot, so a service's interval runs from the
update that named it to the next update — whether or not that next update
mentions the service. A service dropped from the snapshot, or moved to a
level that does not count as downtime, simply stops being impacted there.

⚠️ Returning null rather than an empty array is the whole compatibility
story. No updates means "this incident was created before the log existed",
and the caller falls back to the incident's own start and end. An empty
array would mean "the log says this service was never impacted", which is a
completely different claim and would erase every historical outage.

```php
function segmentsFor(
    PDO $conn,
    int $incidentId,
    int $serviceId,
    string $incidentStart,
    ?string $incidentEnd
): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$incidentId` | `int` |  | the incident (status_incidents.id) |
| `$serviceId` | `int` |  | the service (status_services.id) whose periods to read from the update log |
| `$incidentStart` | `string` |  | when the incident was created (UTC 'Y-m-d H:i:s') |
| `$incidentEnd` | `?string` |  | when it was resolved (UTC 'Y-m-d H:i:s'); null while still open, which runs to now |

**Returns:** array|null

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 216 | `ServiceUptime::segmentsFor` | read [status_incident_updates](/code/tables/status_incident_updates.md), read [status_incident_update_services](/code/tables/status_incident_update_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

### summaryFor

*public static* - line 322

Downtime seconds in the window, and the uptime percentage.

⚠️ OVERLAPS ARE MERGED. Two incidents both taking the same service down at
the same time is one outage, not two — summing their durations would
happily report more downtime than there were seconds in the window, and
an uptime below zero. The intervals are clipped to the window, sorted, and
unioned before anything is added up.

Only levels with counts_as_downtime = 1 are included, so planned
maintenance does not make a well-run service look worse than a neglected
one. That is a per-level setting — see Status → Settings → Impact levels.

```php
function summaryFor(PDO $conn, int $serviceId, int $windowDays): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$serviceId` | `int` |  | the service (status_services.id) |
| `$windowDays` | `int` |  | the window in days, ending now |

**Returns:** array{window_days:int,window_seconds:int,downtime_seconds:int, uptime_percent:float,incident_count:int,counted_count:int}

**Called from:** [api/service-status/get_service_history.php](/code/files/api/service-status/get_service_history.php.md)

### dailyStrip

*public static* - line 382

One entry per day in the window, newest LAST, for the bar strip.

Each day carries the worst counting impact that touched it, so a strip of
90 bars reads as "when was this service unhappy" at a glance. Days with no
incident are 'ok'; days touched only by a non-counting level are 'info',
which is how planned maintenance stays visible without being punitive.

```php
function dailyStrip(PDO $conn, int $serviceId, int $windowDays): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$serviceId` | `int` |  | the service (status_services.id) |
| `$windowDays` | `int` |  | how many days (bars) to return, ending today (UTC) |

**Returns:** array<int,array{date:string,state:string,impact:?string,colour:?string,seconds:int}>

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 389 | `ServiceUptime::dailyStrip` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

**Called from:** [api/service-status/get_service_history.php](/code/files/api/service-status/get_service_history.php.md)

### incidentsBetween

*public static* - line 454

Every incident (or update-log segment) touching this service that overlaps [`$fromUtc`, `$toUtc`). Same shape as `incidentsFor()`. An incident still open runs to now, or to the end of the range if that is earlier.

```php
function incidentsBetween(PDO $conn, int $serviceId, string $fromUtc, string $toUtc): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$serviceId` | `int` |  | the service (status_services.id) |
| `$fromUtc` | `string` |  | the start of the range, inclusive (UTC 'Y-m-d H:i:s') |
| `$toUtc` | `string` |  | the end of the range, exclusive (UTC 'Y-m-d H:i:s') |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 458 | `ServiceUptime::incidentsBetween` | read [status_incident_update_services](/code/tables/status_incident_update_services.md), read [status_incident_updates](/code/tables/status_incident_updates.md) |  |
| 463 | `ServiceUptime::incidentsBetween` | read [status_incidents](/code/tables/status_incidents.md), read [status_incident_services](/code/tables/status_incident_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

**Called from:** [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md)

### summaryBetween

*public static* - line 518

Uptime over [`$fromUtc`, `$toUtc`). Downtime is clipped to the range AND to now, so a range running into the future is measured only up to the present.

```php
function summaryBetween(
    PDO $conn,
    int $serviceId,
    string $fromUtc,
    string $toUtc,
    ?array $incidents = null
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$serviceId` | `int` |  | the service (status_services.id) |
| `$fromUtc` | `string` |  | the start of the range, inclusive (UTC 'Y-m-d H:i:s') |
| `$toUtc` | `string` |  | the end of the range, exclusive (UTC 'Y-m-d H:i:s'); clipped to now |
| `$incidents` | `?array` | `null` | rows already loaded by `incidentsBetween()` for this range, to save a second query; null loads them |

**Returns:** array{range_seconds:int,downtime_seconds:int,uptime_percent:float,incident_count:int,counted_count:int}

**Called from:** [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md)

### dailyStripBetween

*public static* - line 556

One entry per LOCAL day from `$fromDate` to `$toDate` inclusive (Y-m-d in `$tz`), oldest first - the bar strip for a report. Same states as `dailyStrip()`.

```php
function dailyStripBetween(
    PDO $conn,
    int $serviceId,
    string $fromDate,
    string $toDate,
    string $tz,
    ?array $incidents = null
): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$serviceId` | `int` |  | the service (status_services.id) |
| `$fromDate` | `string` |  | the first day (Y-m-d) in `$tz` |
| `$toDate` | `string` |  | the last day (Y-m-d) in `$tz`, inclusive |
| `$tz` | `string` |  | a timezone name for the day boundaries, e.g. 'Europe/London'; an invalid one falls back to UTC |
| `$incidents` | `?array` | `null` | rows already loaded by `incidentsBetween()` covering these days; null loads them |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 569 | `ServiceUptime::dailyStripBetween` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

**Called from:** [includes/report_packs/blocks_status.php](/code/files/includes/report_packs/blocks_status.php.md)

### unionSeconds

*private static* - line 607

Merge overlapping [start, end] pairs and total the result.

The whole reason `summaryFor()` cannot simply SUM durations.

```php
function unionSeconds(array $spans): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$spans` | `array` |  | [start, end] pairs of Unix timestamps, in any order and possibly overlapping |

### spanSeconds

*private static* - line 633

Seconds between two UTC stamps; an open incident runs to now.

```php
function spanSeconds(string $started, ?string $ended): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$started` | `string` |  | the start (UTC 'Y-m-d H:i:s') |
| `$ended` | `?string` |  | the end (UTC 'Y-m-d H:i:s'); null means still open, so now |

### humanDuration

*public static* - line 648

"2h 15m" / "45m" / "3d 4h" — the shape the request asked for.

```php
function humanDuration(int $seconds): string
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$seconds` | `int` |  | a duration in seconds |

**Called from:** [api/service-status/get_service_history.php](/code/files/api/service-status/get_service_history.php.md)

