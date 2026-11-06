# includes/services/morning_checks.php

> PHP - 617 lines

MorningChecksService — the single home for the morning-checks module's write rules (checks, daily results, and the UI-only statuses / reorder / normalise).

Shared by the UI endpoints (api/morning-checks/*.php) and the REST API
(api/v1/resources/morning_checks.php). Each caller passes an ActorContext and
canonical input; this layer validates + writes and returns the affected id(s)
or throws ServiceError. It never emits HTTP.

Canonical behaviour = the API resource's (see docs/design/service-layer.md):
strict date validation (422, not silent-substitute-today), unknown check id
is a 422 (not a raw FK 500), and results are attributed to the acting analyst
(the old UI left CreatedBy NULL). Timestamps are UTC; CheckDate stays a bare
server-local date (the module is a daily ritual).

Input keys are canonical snake_case (name, description, sort_order, is_active,
check_id, status_id/status, date). UI adapters map their camelCase to these.

## Classes

- [MorningChecksService](/code/classes/MorningChecksService.md) (class, line 24)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 157 | `MorningChecksService::listGroups` | read [teams](/code/tables/teams.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

**Included by:** [api/morning-checks/add_check.php](/code/files/api/morning-checks/add_check.php.md), [api/morning-checks/clear_check_result.php](/code/files/api/morning-checks/clear_check_result.php.md), [api/morning-checks/delete_check.php](/code/files/api/morning-checks/delete_check.php.md), [api/morning-checks/delete_group.php](/code/files/api/morning-checks/delete_group.php.md), [api/morning-checks/delete_status.php](/code/files/api/morning-checks/delete_status.php.md), [api/morning-checks/get_groups.php](/code/files/api/morning-checks/get_groups.php.md), [api/morning-checks/get_todays_checks.php](/code/files/api/morning-checks/get_todays_checks.php.md), [api/morning-checks/normalise_status.php](/code/files/api/morning-checks/normalise_status.php.md), [api/morning-checks/reorder_checks.php](/code/files/api/morning-checks/reorder_checks.php.md), [api/morning-checks/reorder_statuses.php](/code/files/api/morning-checks/reorder_statuses.php.md), [api/morning-checks/save_check_result.php](/code/files/api/morning-checks/save_check_result.php.md), [api/morning-checks/save_group.php](/code/files/api/morning-checks/save_group.php.md), [api/morning-checks/save_status.php](/code/files/api/morning-checks/save_status.php.md), [api/morning-checks/update_check.php](/code/files/api/morning-checks/update_check.php.md), [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md)

