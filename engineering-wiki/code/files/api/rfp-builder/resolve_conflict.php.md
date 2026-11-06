# api/rfp-builder/resolve_conflict.php

> API endpoint - 80 lines

Update a conflict's resolution state. Supports choose-A, choose-B, dismiss, label-as-merged, label-as-split, or re-open. Records the acting analyst and timestamp on every transition.

"Merge into one" from the conflicts panel does NOT use this endpoint —
it routes through the existing merge_consolidated.php which deletes
the underlying rows; the conflict row cascade-deletes itself as a
by-product. This endpoint covers the non-destructive resolutions.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/resolve_conflict.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | read [rfp_conflicts](/code/tables/rfp_conflicts.md) |  |
| 53 |  | **update** [rfp_conflicts](/code/tables/rfp_conflicts.md) (`resolution`, `resolution_notes`, `resolved_by_analyst_id`, `resolved_datetime`) |  |
| 63 |  | **update** [rfp_conflicts](/code/tables/rfp_conflicts.md) (`resolution`, `resolution_notes`, `resolved_by_analyst_id`, `resolved_datetime`) |  |
| 73 |  | **update** [rfps](/code/tables/rfps.md) (`updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

