# api/rfp-builder/get_processing_log.php

> API endpoint - 131 lines

Aggregate AI activity for one RFP — totals across runs, plus a slice of the most recent log rows. Powers the "AI activity" panel on the RFP overview page so the user sees what each run cost in tokens.

The `details` column is JSON for success rows (item_count, duration_ms,
cache_read, cache_write, model) and a plain error string for failures.
We surface duration_ms / cache_read in the recent rows so the analyst
can see prompt-cache hits saving them money.

## The endpoint

| | |
|---|---|
| URL | `api/rfp-builder/get_processing_log.php` |
| Protected by | Module access: contracts (requireModuleAccessJson) |
| Reads | `GET rfp_id`, `GET limit` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 47 |  | read [rfp_processing_log](/code/tables/rfp_processing_log.md) |  |
| 61 |  | read [rfp_processing_log](/code/tables/rfp_processing_log.md), read [rfp_documents](/code/tables/rfp_documents.md), read [rfp_output_sections](/code/tables/rfp_output_sections.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rfp_scope.php](/code/files/includes/rfp_scope.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [rfpGate()](/code/functions/rfpGate.md)

