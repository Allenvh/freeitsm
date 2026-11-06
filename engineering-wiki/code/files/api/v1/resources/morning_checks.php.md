# api/v1/resources/morning_checks.php

> API endpoint - 333 lines

FreeITSM REST API v1 — morning-checks resource (checks, results, day board).

Mirrors the module's internal endpoints:
  - checks CRUD mirrors add/update/delete_check.php (delete removes the
    check's results first — here inside a transaction);
  - GET /morning-checks/board is get_todays_checks.php: every ACTIVE check
    with its result for one date, orphan rows (status deleted since the
    result was saved) surfaced with is_orphan + the label snapshot;
  - POST /morning-checks/results is save_check_result.php's upsert — one
    result per check per date (the uq_check_date unique key), status must
    be active, RequiresNotes enforced. 201 on first record, 200 on
    overwrite.

Deliberate differences from the UI, documented:
  - a malformed date is a 422 (the internal endpoints silently substitute
    today — a machine recording yesterday's backfill deserves the error);
  - an unknown check id is a 422 (the UI would hit the FK and 500);
  - results recorded via the API stamp CreatedBy with the key's acting
    analyst (the UI leaves the column NULL).

Dates: CheckDate is a DATETIME holding a plain date (midnight, no TZ
semantics) — the module is a daily operational ritual, so this resource
accepts/returns bare YYYY-MM-DD values; "today" defaults use server-local
date exactly like the dashboard. CreatedDate/ModifiedDate are UTC as usual.
Install-wide (no company scoping — matches the UI); no audit trail exists.

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/morning_checks.php` |
| Protected by | REST API key |
| Reads | `GET is_active`, `GET q`, `GET date`, `GET check_id`, `GET status_id`, `GET from`, `GET to`, `GET orphans` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiSerializeMorningCheck()](/code/functions/apiSerializeMorningCheck.md) | 42 | Shapes a morning check row for the API - name, description, active flag, order and dates. |
| [apiLoadMorningCheck()](/code/functions/apiLoadMorningCheck.md) | 59 | Loads one morning check row, or answers 404 and stops. |
| [apiMorningResultSelect()](/code/functions/apiMorningResultSelect.md) | 70 | Result rows come from this SELECT so the status join is always present. |
| [apiSerializeMorningResult()](/code/functions/apiSerializeMorningResult.md) | 84 | Shapes a morning check result for the API. A result whose status has since been deleted is marked is_orphan, with the label it was saved under. |
| [apiLoadMorningResult()](/code/functions/apiLoadMorningResult.md) | 112 | Loads one morning check result row, or answers 404 and stops. |
| [apiMorningCheckDate()](/code/functions/apiMorningCheckDate.md) | 128 | Strict YYYY-MM-DD — 422 on garbage (the UI silently substitutes today). |
| [apiMorningChecksList()](/code/functions/apiMorningChecksList.md) | 142 | GET /morning-checks/checks — all checks (settings view); ?is_active, ?q |
| [apiMorningChecksGet()](/code/functions/apiMorningChecksGet.md) | 162 | GET /morning-checks/checks/{id} - one morning check. |
| [apiMorningChecksCreate()](/code/functions/apiMorningChecksCreate.md) | 167 | POST /morning-checks/checks |
| [apiMorningChecksUpdate()](/code/functions/apiMorningChecksUpdate.md) | 175 | PATCH /morning-checks/checks/{id} |
| [apiMorningChecksDelete()](/code/functions/apiMorningChecksDelete.md) | 183 | DELETE /morning-checks/checks/{id} |
| [apiMorningChecksBoard()](/code/functions/apiMorningChecksBoard.md) | 196 | GET /morning-checks/board?date=YYYY-MM-DD — get_todays_checks.php's view: every active check with its result for the date (or null if not yet done). |
| [apiMorningCheckResultsList()](/code/functions/apiMorningCheckResultsList.md) | 247 | GET /morning-checks/results — history; newest first |
| [apiMorningCheckResultsGet()](/code/functions/apiMorningCheckResultsGet.md) | 298 | GET /morning-checks/results/{id} - one morning check result. |
| [apiMorningCheckResultsRecord()](/code/functions/apiMorningCheckResultsRecord.md) | 304 | POST /morning-checks/results — save_check_result.php's upsert: one result per check per date. 201 on first record of the day, 200 on overwrite. |
| [apiMorningCheckStatusesList()](/code/functions/apiMorningCheckStatusesList.md) | 317 | GET /morning-check-statuses — full list like get_statuses.php (inactive included so historical results can always be resolved). |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/morning_checks.php](/code/files/includes/services/morning_checks.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiPagination()](/code/functions/apiPagination.md), [apiRespond()](/code/functions/apiRespond.md)

