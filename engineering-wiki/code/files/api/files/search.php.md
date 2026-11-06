# api/files/search.php

> API endpoint - 50 lines

GET ?q= - find folders and files by name, anywhere this person can see. Names only (not file contents). Plain text matches anywhere in the name; with * or ? it is a Windows-style wildcard over the whole name (*.png, report*, invoice-202?-*), as in the folder filter box (nameMatcher() in files-desktop.js). Results are limited to folders the caller holds View on - the search is the same permission check as browsing, run over every folder at once. Every search is audited with what was asked.

## The endpoint

| | |
|---|---|
| URL | `api/files/search.php` |
| Protected by | Module access: files (requireModuleAccessJson) (via includes/files/api_bootstrap.php) |
| Reads | `GET q` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 36 |  | read [files_items](/code/tables/files_items.md) |  |

## How it hangs together

**Includes:** [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md)

**Calls:** [filesApiOk()](/code/functions/filesApiOk.md), [filesApiRun()](/code/functions/filesApiRun.md), [filesAudit()](/code/functions/filesAudit.md)

