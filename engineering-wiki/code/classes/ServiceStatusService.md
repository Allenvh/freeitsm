# ServiceStatusService

> Class in [includes/services/service_status.php](/code/files/includes/services/service_status.php.md), line 21

## Methods

| Method | What it does |
|---|---|
| [saveService()](#saveservice) | Create (no id) or update (id present) a service. Returns the id. |
| [deleteService()](#deleteservice) | Delete a service + its incident links, atomically. |
| [loadServiceRow()](#loadservicerow) | Loads one service; throws not found when it does not exist. |
| [saveIncident()](#saveincident) | Create (no id) or update (id present) an incident. Returns the id. |
| [updateIncident()](#updateincident) | Changes an incident's title, status, comment and affected services, logging an update when the status, comment or services changed, and fires service_status.incident_updated (and incident_resolved). |
| [deleteIncident()](#deleteincident) | Delete an incident + its service links, atomically. |
| [loadIncidentRow()](#loadincidentrow) | Loads one incident with whether its status counts as resolved; throws not found when it does not exist. |
| [resolveIncidentStatus()](#resolveincidentstatus) | Resolve an incident status by id or name — strict on unknown/inactive. |
| [validateIncidentServices()](#validateincidentservices) | Validate [{service_id, impact_level\|impact_level_id}] → [[service_id, impact_id]]. Strict: 422 on unknown service or impact (the old UI silently skipped these). |
| [incidentServicesDiffer()](#incidentservicesdiffer) | Do the services (and their impacts) about to be written differ from what is stored? Used to decide whether a save has anything new to say. |
| [editIncidentUpdate()](#editincidentupdate) | Correct an update that has already been posted (Ed, on top of #99). |
| [deleteIncidentUpdate()](#deleteincidentupdate) | Remove one update from the timeline. |
| [replaceIncidentServices()](#replaceincidentservices) | Replaces the services an incident affects, each with its impact level. |
| [recordIncidentUpdate()](#recordincidentupdate) | Record a moment in an incident's life (discussion #59, phase 2). |
| [saveIncidentStatus()](#saveincidentstatus) | Adds or updates an incident status; making one the default clears the flag on the others. |
| [deleteIncidentStatus()](#deleteincidentstatus) | Deletes an incident status; refuses the default status and any status still used by an incident. |
| [saveImpactLevel()](#saveimpactlevel) | Adds or updates an impact level, including whether it counts as downtime; making one the default clears the flag on the others. |
| [deleteImpactLevel()](#deleteimpactlevel) | Deletes an impact level; refuses the default level and any level still used on an incident. |

### saveService

*public static* - line 33

Create (no id) or update (id present) a service. Returns the id.

```php
function saveService(PDO $conn, ActorContext $ctx, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (a service to change; absent creates one), name, description, display_order, is_active - on update, keys left out keep their value |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 | `ServiceStatusService::saveService` | **update** [status_services](/code/tables/status_services.md) (`name`, `description`, `display_order`, `is_active`) |  |
| 61 | `ServiceStatusService::saveService` | **insert** [status_services](/code/tables/status_services.md) (`name`, `description`, `display_order`, `is_active`, `created_datetime`) |  |

**Called from:** [api/service-status/save_service.php](/code/files/api/service-status/save_service.php.md), [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md)

### deleteService

*public static* - line 79

Delete a service + its incident links, atomically.

```php
function deleteService(PDO $conn, ActorContext $ctx, int $id): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the service (status_services.id) to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 84 | `ServiceStatusService::deleteService` | **delete** [status_incident_services](/code/tables/status_incident_services.md) |  |
| 85 | `ServiceStatusService::deleteService` | **delete** [status_services](/code/tables/status_services.md) |  |

**Called from:** [api/service-status/delete_service.php](/code/files/api/service-status/delete_service.php.md), [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md)

### loadServiceRow

*private static* - line 99

Loads one service; throws not found when it does not exist.

```php
function loadServiceRow(PDO $conn, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the service (status_services.id) to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 101 | `ServiceStatusService::loadServiceRow` | read [status_services](/code/tables/status_services.md) |  |

### saveIncident

*public static* - line 118

Create (no id) or update (id present) an incident. Returns the id.

```php
function saveIncident(PDO $conn, ActorContext $ctx, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (an incident to change; absent creates one), title, status_id or status (a name; default Investigating), comment, is_internal (default true), services - a list of {service_id, impact_level_id or impact_level} |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 141 | `ServiceStatusService::saveIncident` | **insert** [status_incidents](/code/tables/status_incidents.md) (`title`, `status_id`, `comment`, `created_by_id`, `created_datetime`, `updated_datetime`, `resolved_datetime`) |  |

**Called from:** [api/service-status/save_incident.php](/code/files/api/service-status/save_incident.php.md), [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md), [includes/domains/status_link.php](/code/files/includes/domains/status_link.php.md), [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md)

### updateIncident

*private static* - line 171

Changes an incident's title, status, comment and affected services, logging an update when the status, comment or services changed, and fires service_status.incident_updated (and incident_resolved).

An update is internal unless is_internal is sent as false.

```php
function updateIncident(PDO $conn, ActorContext $ctx, int $id, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the incident (status_incidents.id) to change |
| `$in` | `array` |  | only the fields to change: title, status_id or status, comment, is_internal (default true), services |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 207 | `ServiceStatusService::updateIncident` | **update** [status_incidents](/code/tables/status_incidents.md) (`title`, `status_id`, `comment`, `resolved_datetime`, `updated_datetime`) |  |
| 213 | `ServiceStatusService::updateIncident` | **update** [status_incidents](/code/tables/status_incidents.md) (`title`, `status_id`, `comment`, `resolved_datetime`, `updated_datetime`) |  |

### deleteIncident

*public static* - line 266

Delete an incident + its service links, atomically.

```php
function deleteIncident(PDO $conn, ActorContext $ctx, int $id): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the incident (status_incidents.id) to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 271 | `ServiceStatusService::deleteIncident` | **delete** [status_incident_services](/code/tables/status_incident_services.md) |  |
| 272 | `ServiceStatusService::deleteIncident` | **delete** [status_incidents](/code/tables/status_incidents.md) |  |

**Called from:** [api/service-status/delete_incident.php](/code/files/api/service-status/delete_incident.php.md), [api/v1/resources/service_status.php](/code/files/api/v1/resources/service_status.php.md)

### loadIncidentRow

*private static* - line 287

Loads one incident with whether its status counts as resolved; throws not found when it does not exist.

```php
function loadIncidentRow(PDO $conn, int $id): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the incident (status_incidents.id) to load |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 290 | `ServiceStatusService::loadIncidentRow` | read [status_incidents](/code/tables/status_incidents.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |

### resolveIncidentStatus

*private static* - line 306

Resolve an incident status by id or name — strict on unknown/inactive.

```php
function resolveIncidentStatus(PDO $conn, array $in): ?array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the request: status_id wins over status (a name); neither gives null, unknown or inactive throws |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 309 | `ServiceStatusService::resolveIncidentStatus` | read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 312 | `ServiceStatusService::resolveIncidentStatus` | read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |

### validateIncidentServices

*public static* - line 331

Validate [{service_id, impact_level|impact_level_id}] → [[service_id, impact_id]]. Strict: 422 on unknown service or impact (the old UI silently skipped these).

```php
function validateIncidentServices(PDO $conn, array $services): array
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$services` | `array` |  | the affected services as sent: a list of {service_id, impact_level_id or impact_level (a name)}; no impact means the default impact level |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 339 | `ServiceStatusService::validateIncidentServices` | read [status_services](/code/tables/status_services.md) |  |
| 345 | `ServiceStatusService::validateIncidentServices` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 354 | `ServiceStatusService::validateIncidentServices` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

**Called from:** [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md)

### incidentServicesDiffer

*private static* - line 378

Do the services (and their impacts) about to be written differ from what is stored? Used to decide whether a save has anything new to say.

Order-insensitive: the dialog rebuilds its rows every time it opens, so
the same two services can arrive in a different order without anything
having actually changed — and appending on that would be the same bug in
a different coat.

```php
function incidentServicesDiffer(PDO $conn, int $incidentId, array $links): bool
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$incidentId` | `int` |  | the incident (status_incidents.id) whose stored links to compare |
| `$links` | `array` |  | the validated links about to be written, as [[serviceId, impactId], ...] |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 380 | `ServiceStatusService::incidentServicesDiffer` | read [status_incident_services](/code/tables/status_incident_services.md) |  |

### editIncidentUpdate

*public static* - line 412

Correct an update that has already been posted (Ed, on top of #99).

⚠️ EDIT IN PLACE, not a new entry. Fixing a typo used to mean saving the
incident again, which published the sentence twice with the wrong version
first — and on a customer-facing timeline that reads as carelessness.

Only the wording and the visibility can change. The status and the service
impacts recorded on an update are what was true at that moment, and
rewriting them would make the timeline a record of what somebody wishes
had happened.

```php
function editIncidentUpdate(PDO $conn, ActorContext $ctx, int $updateId, array $in): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$updateId` | `int` |  | the timeline entry (status_incident_updates.id) to correct |
| `$in` | `array` |  | comment and/or is_internal; a key left out keeps its value |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 414 | `ServiceStatusService::editIncidentUpdate` | read [status_incident_updates](/code/tables/status_incident_updates.md) |  |
| 428 | `ServiceStatusService::editIncidentUpdate` | **update** [status_incident_updates](/code/tables/status_incident_updates.md) (`comment`, `is_internal`) |  |
| 434 | `ServiceStatusService::editIncidentUpdate` | read [status_incident_updates](/code/tables/status_incident_updates.md) |  |
| 437 | `ServiceStatusService::editIncidentUpdate` | **update** [status_incidents](/code/tables/status_incidents.md) (`comment`) |  |

**Called from:** [api/service-status/save_incident_update.php](/code/files/api/service-status/save_incident_update.php.md)

### deleteIncidentUpdate

*public static* - line 451

Remove one update from the timeline.

The incident and every other update survive it. As with editing, the
incident's own comment follows if the one removed was the latest.

```php
function deleteIncidentUpdate(PDO $conn, ActorContext $ctx, int $updateId): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$updateId` | `int` |  | the timeline entry (status_incident_updates.id) to remove |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 453 | `ServiceStatusService::deleteIncidentUpdate` | read [status_incident_updates](/code/tables/status_incident_updates.md) |  |
| 461 | `ServiceStatusService::deleteIncidentUpdate` | **delete** [status_incident_updates](/code/tables/status_incident_updates.md) |  |
| 464 | `ServiceStatusService::deleteIncidentUpdate` | read [status_incident_updates](/code/tables/status_incident_updates.md) |  |
| 469 | `ServiceStatusService::deleteIncidentUpdate` | **update** [status_incidents](/code/tables/status_incidents.md) (`comment`) |  |

**Called from:** [api/service-status/delete_incident_update.php](/code/files/api/service-status/delete_incident_update.php.md)

### replaceIncidentServices

*private static* - line 479

Replaces the services an incident affects, each with its impact level.

```php
function replaceIncidentServices(PDO $conn, int $incidentId, array $links): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$incidentId` | `int` |  | the incident (status_incidents.id) |
| `$links` | `array` |  | the validated links, as [[serviceId, impactId], ...]; empty removes them all |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 481 | `ServiceStatusService::replaceIncidentServices` | **delete** [status_incident_services](/code/tables/status_incident_services.md) |  |
| 483 | `ServiceStatusService::replaceIncidentServices` | **insert** [status_incident_services](/code/tables/status_incident_services.md) (`incident_id`, `service_id`, `impact_level_id`) |  |

### recordIncidentUpdate

*private static* - line 519

Record a moment in an incident's life (discussion #59, phase 2).

⚠️ THIS IS WHY DOWNGRADING A SERVICE NO LONGER LOSES THE EARLIER LEVEL.
`replaceIncidentServices()` above deletes and re-inserts, so
status_incident_services only ever holds the CURRENT impact — moving a
service from Major Outage to Degraded overwrote the first value and the
history then reported the whole incident at whatever it ended on.

Each call appends a full SNAPSHOT of the per-service impacts, not a diff.
A diff would make the reader reconstruct state, and one missing row would
silently shift a service's entire timeline; a snapshot costs a few rows
and cannot drift.

A service is "restored" either by moving it to a level that does not count
as downtime, or by dropping it from `$links` entirely. Both simply end its
interval at this update, which is why there is no per-service resolved flag.

Best-effort by design: an install that has not run Database Verification
since this shipped has no tables to write to, and failing to save an
incident because its audit trail could not be appended would be a worse
outcome than the missing trail. The reader falls back for such incidents.

```php
function recordIncidentUpdate(
    PDO $conn,
    ActorContext $ctx,
    int $incidentId,
    ?int $statusId,
    ?string $comment,
    ?array $links,
    bool $isInternal = true
): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$incidentId` | `int` |  | the incident (status_incidents.id) the entry belongs to |
| `$statusId` | `?int` |  | the incident's status at this moment, or null |
| `$comment` | `?string` |  | what was said; null when only the status or services changed |
| `$links` | `?array` |  | [[serviceId, impactId], …] — null means "unchanged", in which case the current links are snapshotted. |
| `$isInternal` | `bool` | `true` | true keeps the entry off the self-service portal (the default) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 523 | `ServiceStatusService::recordIncidentUpdate` | read [status_incident_services](/code/tables/status_incident_services.md) |  |
| 532 | `ServiceStatusService::recordIncidentUpdate` | **insert** [status_incident_updates](/code/tables/status_incident_updates.md) (`incident_id`, `status_id`, `comment`, `is_internal`, `created_by_id`, `created_datetime`) |  |
| 538 | `ServiceStatusService::recordIncidentUpdate` | **insert** [status_incident_update_services](/code/tables/status_incident_update_services.md) (`update_id`, `service_id`, `impact_level_id`) |  |

### saveIncidentStatus

*public static* - line 558

Adds or updates an incident status; making one the default clears the flag on the others.

```php
function saveIncidentStatus(PDO $conn, ActorContext $ctx, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (to change one), name (required), colour (#rrggbb), is_resolved, is_default, display_order, is_active |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 576 | `ServiceStatusService::saveIncidentStatus` | **update** [service_incident_statuses](/code/tables/service_incident_statuses.md) (`is_default`) |  |
| 582 | `ServiceStatusService::saveIncidentStatus` | **update** [service_incident_statuses](/code/tables/service_incident_statuses.md) (`name`, `colour`, `is_resolved`, `is_default`, `display_order`, `is_active`) |  |
| 586 | `ServiceStatusService::saveIncidentStatus` | **insert** [service_incident_statuses](/code/tables/service_incident_statuses.md) (`name`, `colour`, `is_resolved`, `is_default`, `display_order`, `is_active`) |  |
| 590 | `ServiceStatusService::saveIncidentStatus` | read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 592 | `ServiceStatusService::saveIncidentStatus` | **update** [service_incident_statuses](/code/tables/service_incident_statuses.md) (`is_default`) |  |

**Called from:** [api/service-status/save_incident_status.php](/code/files/api/service-status/save_incident_status.php.md)

### deleteIncidentStatus

*public static* - line 608

Deletes an incident status; refuses the default status and any status still used by an incident.

```php
function deleteIncidentStatus(PDO $conn, ActorContext $ctx, int $id): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the incident status (service_incident_statuses.id) to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 612 | `ServiceStatusService::deleteIncidentStatus` | read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 616 | `ServiceStatusService::deleteIncidentStatus` | read [status_incidents](/code/tables/status_incidents.md) |  |
| 622 | `ServiceStatusService::deleteIncidentStatus` | read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 623 | `ServiceStatusService::deleteIncidentStatus` | **delete** [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |

**Called from:** [api/service-status/delete_incident_status.php](/code/files/api/service-status/delete_incident_status.php.md)

### saveImpactLevel

*public static* - line 638

Adds or updates an impact level, including whether it counts as downtime; making one the default clears the flag on the others.

```php
function saveImpactLevel(PDO $conn, ActorContext $ctx, array $in): int
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | id (to change one), name (required), colour (#rrggbb), is_default, severity_order (default 99), display_order, is_active, counts_as_downtime (absent means 1) |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 663 | `ServiceStatusService::saveImpactLevel` | **update** [service_impact_levels](/code/tables/service_impact_levels.md) (`is_default`) |  |
| 669 | `ServiceStatusService::saveImpactLevel` | **update** [service_impact_levels](/code/tables/service_impact_levels.md) (`name`, `colour`, `is_default`, `severity_order`, `display_order`, `is_active`, `counts_as_downtime`) |  |
| 673 | `ServiceStatusService::saveImpactLevel` | **insert** [service_impact_levels](/code/tables/service_impact_levels.md) (`name`, `colour`, `is_default`, `severity_order`, `display_order`, `is_active`, `counts_as_downtime`) |  |
| 677 | `ServiceStatusService::saveImpactLevel` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 679 | `ServiceStatusService::saveImpactLevel` | **update** [service_impact_levels](/code/tables/service_impact_levels.md) (`is_default`) |  |

**Called from:** [api/service-status/save_impact_level.php](/code/files/api/service-status/save_impact_level.php.md)

### deleteImpactLevel

*public static* - line 695

Deletes an impact level; refuses the default level and any level still used on an incident.

```php
function deleteImpactLevel(PDO $conn, ActorContext $ctx, int $id): void
```

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the impact level (service_impact_levels.id) to delete |

**Database**

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 699 | `ServiceStatusService::deleteImpactLevel` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 703 | `ServiceStatusService::deleteImpactLevel` | read [status_incident_services](/code/tables/status_incident_services.md) |  |
| 709 | `ServiceStatusService::deleteImpactLevel` | read [service_impact_levels](/code/tables/service_impact_levels.md) |  |
| 710 | `ServiceStatusService::deleteImpactLevel` | **delete** [service_impact_levels](/code/tables/service_impact_levels.md) |  |

**Called from:** [api/service-status/delete_impact_level.php](/code/files/api/service-status/delete_impact_level.php.md)

