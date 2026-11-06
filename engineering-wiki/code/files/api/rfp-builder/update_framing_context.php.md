# api/rfp-builder/update_framing_context.php

> API endpoint - 51 lines

Save the optional "context note" the analyst supplies for an RFP — the short paragraph explaining why the procurement is happening, what the organisation is replacing, etc. Fed into the framing- generation prompt so the introduction doesn't have to guess at the business context.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/update_framing_context.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 |  | read [rfps](/code/tables/rfps.md) |  |
| 40 |  | **update** [rfps](/code/tables/rfps.md) (`framing_context_text`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

