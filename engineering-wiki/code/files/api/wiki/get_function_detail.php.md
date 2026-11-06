# api/wiki/get_function_detail.php

> API endpoint - 60 lines

API Endpoint: Get function detail with callers Query param: ?id=456

## The endpoint

| | |
|---|---|
| URL | `api/wiki/get_function_detail.php` |
| Protected by | Module access: wiki (requireModuleAccessJson) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [wiki_functions](/code/tables/wiki_functions.md), read [wiki_files](/code/tables/wiki_files.md) |  |
| 42 |  | read [wiki_function_calls](/code/tables/wiki_function_calls.md), read [wiki_files](/code/tables/wiki_files.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

