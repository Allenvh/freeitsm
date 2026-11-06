# api/system/fix_orphans.php

> API endpoint - 92 lines

API Endpoint: Fix orphaned child rows that block a ticket-child foreign key.

"Orphans" are rows whose parent has already been deleted — e.g.
email_attachments left behind when an email was removed but its attachment
rows weren't. They are unreachable dead data and are exactly what MySQL
rejects when adding the foreign key. The Database Verify page surfaces them
with a Fix button that calls this endpoint, then re-runs verification (which
then succeeds in adding the FK).

Strictly whitelisted: only the four known ticket-child tables, and only rows
with no matching parent are ever deleted.

## The endpoint

| | |
|---|---|
| URL | `api/system/fix_orphans.php` |
| Protected by | Administrators only (admin_api_guard.php) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md)

