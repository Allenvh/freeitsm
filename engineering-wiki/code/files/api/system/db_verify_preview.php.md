# api/system/db_verify_preview.php

> API endpoint - 55 lines

API Endpoint: Database Verification — preview (READ ONLY).

Answers "what would Database Verification change?" without changing anything.
See includes/db_verify_preview.php for what this can and cannot promise.

Deliberately a separate endpoint rather than a ?dry_run=1 flag on db_verify.php:
a flag on the destructive endpoint is one typo away from running the real thing,
and it would mean the read-only path and the migration path share a file that
`exec()`s DDL. This file contains no ALTER, no CREATE and no DROP at all, which is
a property you can verify by reading it rather than by trusting a conditional.

## The endpoint

| | |
|---|---|
| URL | `api/system/db_verify_preview.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/setup_state.php](/code/files/includes/setup_state.php.md), [includes/db_verify_preview.php](/code/files/includes/db_verify_preview.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/db_verify_schema.php](/code/files/includes/db_verify_schema.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [dbVerifyPreview()](/code/functions/dbVerifyPreview.md), [installIsUnprovisioned()](/code/functions/installIsUnprovisioned.md)

