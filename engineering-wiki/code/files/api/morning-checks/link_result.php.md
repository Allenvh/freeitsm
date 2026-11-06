# api/morning-checks/link_result.php

> API endpoint - 52 lines

API: record that a ticket or task was raised from a morning check (#64).

A breadcrumb, written after the ticket or task already exists. It is
deliberately not part of that creation: the follow-up is the valuable thing,
and losing the note of where it came from must never look like the ticket
failed to be raised.

## The endpoint

| | |
|---|---|
| URL | `api/morning-checks/link_result.php` |
| Protected by | Module access: morning-checks (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

