# api/change-management/submit_cab_vote.php

> API endpoint - 41 lines

API Endpoint: Submit a CAB vote. Thin UI adapter over ChangesService::voteCab — records the vote and applies the auto-transition (any required Reject → Draft; the all/majority threshold on required members → Approved, firing change.approved).

## The endpoint

| | |
|---|---|
| URL | `api/change-management/submit_cab_vote.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/changes.php](/code/files/includes/services/changes.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

