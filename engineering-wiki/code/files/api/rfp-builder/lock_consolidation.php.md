# api/rfp-builder/lock_consolidation.php

> API endpoint - 89 lines

Lock or unlock all consolidated requirements for an RFP. Locking is the gate for Phase 4 (section generation) — once locked the analyst has signed off the consolidated set and downstream generation can trust that the inputs won't shift underneath it. Unlock to re-enter editing mode.

Also nudges the parent RFP's status: locking moves it from
'collecting'/'consolidating' to 'generating' (i.e. ready for Phase 4);
unlocking moves a status of 'generating' back to 'consolidating' so
the analyst's intent is reflected in the RFP list.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/lock_consolidation.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 40 |  | read [rfps](/code/tables/rfps.md) |  |
| 44 |  | read [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) |  |
| 54 |  | **update** [rfp_consolidated_requirements](/code/tables/rfp_consolidated_requirements.md) (`is_locked`, `updated_datetime`) |  |
| 65 |  | **update** [rfps](/code/tables/rfps.md) (`status`, `updated_datetime`) |  |
| 72 |  | **update** [rfps](/code/tables/rfps.md) (`status`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

