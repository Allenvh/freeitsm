# api/wiki/get_file_detail.php

> API endpoint - 92 lines

API Endpoint: Get full detail for a single file Query param: ?id=123

## The endpoint

| | |
|---|---|
| URL | `api/wiki/get_file_detail.php` |
| Protected by | Module access: wiki (requireModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [wiki_files](/code/tables/wiki_files.md) |  |
| 38 |  | read [wiki_functions](/code/tables/wiki_functions.md) |  |
| 43 |  | read [wiki_classes](/code/tables/wiki_classes.md) |  |
| 48 |  | read [wiki_dependencies](/code/tables/wiki_dependencies.md), read [wiki_files](/code/tables/wiki_files.md) |  |
| 58 |  | read [wiki_dependencies](/code/tables/wiki_dependencies.md), read [wiki_files](/code/tables/wiki_files.md) |  |
| 68 |  | read [wiki_db_references](/code/tables/wiki_db_references.md) |  |
| 73 |  | read [wiki_session_vars](/code/tables/wiki_session_vars.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

