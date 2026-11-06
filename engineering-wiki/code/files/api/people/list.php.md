# api/people/list.php

> API endpoint - 35 lines

People (#153 step 2) — GET ?q=&company=&status=active|leavers|all The people this analyst can see: only companies they can access.

## The endpoint

| | |
|---|---|
| URL | `api/people/list.php` |
| Protected by | Module access: people (requireModuleAccessJson) |
| Reads | `GET status`, `GET q`, `GET company` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/people.php](/code/files/includes/people.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [peopleListRows()](/code/functions/peopleListRows.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

